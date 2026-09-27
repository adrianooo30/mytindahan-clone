<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePosSaleRequest;
use App\Models\Customer;
use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PosController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $products = $store->products()
            ->where('is_active', true)
            ->with('inventoryBatches')
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price,
                'stock' => (int) $product->inventoryBatches->sum('remaining_quantity'),
            ])
            ->values()
            ->all();

        $customers = $store->customers()
            ->orderBy('name')
            ->get()
            ->map(fn (Customer $customer) => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'balance' => (int) $customer->transactions()->sum('amount'),
            ])
            ->values()
            ->all();

        return Inertia::render('pos', [
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function store(StorePosSaleRequest $request)
    {
        $store = $request->user()->stores()->firstOrFail();
        $validated = $request->validated();

        $subtotal = 0;

        foreach ($validated['items'] as $item) {
            $subtotal += (int) $item['unit_price'] * (int) $item['quantity'];
        }

        $discount = (int) ($validated['discount'] ?? 0);
        $total = max(0, $subtotal - $discount);
        $amountPaid = (int) ($validated['amount_paid'] ?? 0);
        $paymentMethod = $validated['payment_method'];

        return DB::transaction(function () use ($store, $validated, $total, $discount, $paymentMethod, $amountPaid) {
            $customer = isset($validated['customer_id']) ? $store->customers()->findOrFail($validated['customer_id']) : null;

            $sale = $store->sales()->create([
                'customer_id' => $customer?->id,
                'invoice_number' => 'INV-'.now()->format('YmdHis').'-'.random_int(1000, 9999),
                'subtotal' => $total + $discount,
                'discount' => $discount,
                'tax' => 0,
                'total' => $total,
                'payment_status' => $amountPaid >= $total ? 'paid' : (($amountPaid > 0) ? 'partial' : 'pending'),
                'sold_at' => now(),
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $product = $store->products()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];
                $remaining = $quantity;

                $batches = $product->inventoryBatches()->orderBy('expires_at')->orderBy('id')->get();
                foreach ($batches as $batch) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $deduct = min((int) $batch->remaining_quantity, $remaining);
                    if ($deduct <= 0) {
                        continue;
                    }

                    $batch->decrement('remaining_quantity', $deduct);
                    $remaining -= $deduct;
                }

                if ($remaining > 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Not enough stock available for '.$product->name.'.',
                    ]);
                }

                $sale->items()->create([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => (int) $item['unit_price'],
                    'cost_price' => (int) $product->cost_price,
                    'discount' => 0,
                    'subtotal' => (int) $item['unit_price'] * $quantity,
                ]);
            }

            if ($amountPaid > 0) {
                $payment = $sale->payments()->create([
                    'store_id' => $store->id,
                    'payment_method' => $paymentMethod,
                    'amount' => min($amountPaid, $total),
                    'reference_number' => $paymentMethod === 'cash' ? 'CASH-'.now()->format('YmdHis') : ($paymentMethod === 'gcash' ? 'GCASH-'.now()->format('YmdHis') : null),
                    'paid_at' => now(),
                ]);

                if ($paymentMethod === 'gcash') {
                    $store->gcashTransactions()->create([
                        'type' => 'customer_payment',
                        'amount' => $payment->amount,
                        'reference_number' => $payment->reference_number,
                        'description' => 'Sale payment for '.$sale->invoice_number,
                        'transaction_date' => now()->toDateString(),
                    ]);
                }
            }

            if ($customer && $paymentMethod === 'utang') {
                $currentBalance = (int) $customer->transactions()->where('type', 'debt')->sum('amount')
                    - (int) $customer->transactions()->where('type', 'payment')->sum('amount');

                $customer->transactions()->create([
                    'store_id' => $store->id,
                    'sale_id' => $sale->id,
                    'type' => 'debt',
                    'amount' => $total,
                    'balance_after' => $currentBalance + $total,
                    'description' => 'Utang sale invoice '.$sale->invoice_number,
                    'transaction_date' => now()->toDateString(),
                ]);
            }

            return redirect()->route('pos')->with('success', 'Sale recorded successfully.');
        });
    }
}
