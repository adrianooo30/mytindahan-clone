<?php

namespace App\Http\Controllers;

use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $alerts = $store->products()
            ->with('inventoryBatches')
            ->get()
            ->flatMap(function (Product $product): array {
                $alerts = [];
                $quantity = (int) $product->inventoryBatches->sum('remaining_quantity');

                if ($quantity <= 5 || ($product->reorder_level > 0 && $quantity <= $product->reorder_level)) {
                    $alerts[] = [
                        'type' => 'low_stock',
                        'title' => 'Low stock: '.$product->name,
                        'message' => 'Only '.$quantity.' units remain in stock.',
                    ];
                }

                $expiringBatches = $product->inventoryBatches
                    ->filter(fn (InventoryBatch $batch) => ! is_null($batch->expires_at))
                    ->filter(fn (InventoryBatch $batch) => $batch->expires_at->lte(now()->addDays(7)))
                    ->sortBy('expires_at');

                foreach ($expiringBatches as $batch) {
                    $alerts[] = [
                        'type' => 'expiring_soon',
                        'title' => 'Expiring soon: '.$product->name,
                        'message' => $batch->product?->name.' expires on '.$batch->expires_at?->format('M d, Y').'.',
                    ];
                }

                return $alerts;
            })
            ->values()
            ->all();

        return Inertia::render('notifications', [
            'alerts' => $alerts,
        ]);
    }
}
