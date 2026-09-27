<?php

namespace App\Http\Controllers;

use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $salesToday = $store->sales()
            ->whereDate('sold_at', today())
            ->get();

        $todaySales = (int) $salesToday->sum('total');
        $transactionCount = $salesToday->count();
        $inventoryValue = $this->inventoryValue($store);
        $outstandingUtang = $this->outstandingUtang($store);
        $lowStock = $this->lowStock($store);
        $expiringSoon = $this->expiringSoon($store);

        return Inertia::render('dashboard', [
            'todaySales' => $todaySales,
            'transactionCount' => $transactionCount,
            'inventoryValue' => $inventoryValue,
            'outstandingUtang' => $outstandingUtang,
            'lowStock' => $lowStock,
            'expiringSoon' => $expiringSoon,
        ]);
    }

    protected function inventoryValue($store): int
    {
        return (int) $store->products()
            ->with('inventoryBatches')
            ->get()
            ->sum(fn (Product $product) => $product->inventoryBatches->sum(
                fn (InventoryBatch $batch) => (int) $batch->remaining_quantity * (int) $batch->cost_price
            ));
    }

    protected function outstandingUtang($store): int
    {
        return (int) $store->customers()
            ->with('transactions')
            ->get()
            ->sum(function ($customer) {
                $debt = (int) $customer->transactions()->where('type', 'debt')->sum('amount');
                $payments = (int) $customer->transactions()->where('type', 'payment')->sum('amount');

                return max(0, $debt - $payments);
            });
    }

    protected function lowStock($store): array
    {
        return $store->products()
            ->with('inventoryBatches')
            ->get()
            ->map(function (Product $product) {
                $quantity = (int) $product->inventoryBatches->sum('remaining_quantity');

                if ($quantity <= 5 || ($product->reorder_level > 0 && $quantity <= $product->reorder_level)) {
                    return [
                        'name' => $product->name,
                        'qty' => $quantity,
                    ];
                }

                return null;
            })
            ->filter()
            ->take(5)
            ->values()
            ->all();
    }

    protected function expiringSoon($store): array
    {
        return $store->products()
            ->with('inventoryBatches')
            ->get()
            ->flatMap(fn (Product $product) => $product->inventoryBatches)
            ->filter(fn (InventoryBatch $batch) => ! is_null($batch->expires_at))
            ->sortBy('expires_at')
            ->take(5)
            ->map(fn (InventoryBatch $batch) => [
                'name' => $batch->product?->name ?? 'Batch item',
                'date' => $batch->expires_at?->format('M d') ?? 'N/A',
            ])
            ->values()
            ->all();
    }
}
