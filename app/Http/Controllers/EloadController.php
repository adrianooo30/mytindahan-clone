<?php

namespace App\Http\Controllers;

use App\Models\EloadTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EloadController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $transactions = $store->eloadTransactions()
            ->latest('sold_at')
            ->get()
            ->map(fn (EloadTransaction $transaction) => [
                'id' => $transaction->id,
                'customerId' => $transaction->customer_id,
                'mobileNumber' => $transaction->mobile_number,
                'network' => $transaction->network,
                'productName' => $transaction->product_name,
                'amount' => (int) $transaction->amount,
                'referenceNumber' => $transaction->reference_number,
                'status' => $transaction->status,
                'soldAt' => $transaction->sold_at?->format('Y-m-d H:i:s'),
            ])
            ->values()
            ->all();

        return Inertia::render('eload', [
            'transactions' => $transactions,
        ]);
    }

    public function store(Request $request)
    {
        $store = $request->user()->stores()->firstOrFail();

        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'network' => ['required', 'string', 'max:50'],
            'product_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:pending,successful,failed,refunded'],
        ]);

        $store->eloadTransactions()->create([
            'customer_id' => $data['customer_id'] ?? null,
            'mobile_number' => $data['mobile_number'],
            'network' => $data['network'],
            'product_name' => $data['product_name'],
            'amount' => $data['amount'],
            'reference_number' => $data['reference_number'] ?? null,
            'status' => $data['status'],
            'sold_at' => now(),
        ]);

        return redirect()->route('eload')->with('success', 'E-load transaction recorded successfully.');
    }
}
