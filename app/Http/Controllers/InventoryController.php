<?php

namespace App\Http\Controllers;

use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $products = $store->products()
            ->with('inventoryBatches')
            ->get();

        $totalSkus = $products->count();
        $totalStock = (int) $products->sum(fn (Product $product) => $product->inventoryBatches->sum('remaining_quantity'));
        $criticalItems = $products->filter(fn (Product $product) => $this->isCritical($product))->count();
        $inventoryValue = (int) $products->sum(fn (Product $product) => $product->inventoryBatches->sum(
            fn (InventoryBatch $batch) => (int) $batch->remaining_quantity * (int) $batch->cost_price
        ));

        $stockLevels = $products->map(function (Product $product) {
            $quantity = (int) $product->inventoryBatches->sum('remaining_quantity');
            $reorderLevel = (int) $product->reorder_level;

            return [
                'name' => $product->name,
                'qty' => $quantity,
                'reorder' => $reorderLevel ?: 0,
                'status' => $this->isCritical($product) ? 'Critical' : ($quantity <= 0 ? 'Out of stock' : 'Healthy'),
            ];
        })->values()->all();

        return Inertia::render('inventory', [
            'totalSkus' => $totalSkus,
            'totalStock' => $totalStock,
            'criticalItems' => $criticalItems,
            'inventoryValue' => $inventoryValue,
            'stockLevels' => $stockLevels,
        ]);
    }

    protected function isCritical(Product $product): bool
    {
        $quantity = (int) $product->inventoryBatches->sum('remaining_quantity');

        return $quantity <= 5 || ($product->reorder_level > 0 && $quantity <= $product->reorder_level);
    }
}
