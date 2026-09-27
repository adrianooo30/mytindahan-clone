<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $salesTotal = (int) $store->sales()->where('payment_status', '!=', 'pending')->sum('total');

        $profitTotal = (int) $store->sales()
            ->with('items')
            ->get()
            ->sum(fn (Sale $sale) => $sale->items->sum(fn ($item) => ((int) $item->unit_price - (int) $item->cost_price) * (int) $item->quantity));

        $utangBalance = (int) $store->customers()
            ->with('transactions')
            ->get()
            ->sum(function (Customer $customer) {
                $debt = (int) $customer->transactions()->where('type', 'debt')->sum('amount');
                $payment = (int) $customer->transactions()->where('type', 'payment')->sum('amount');

                return $debt - $payment;
            });

        $expenseTotal = (int) $store->expenses()->sum('amount');

        return Inertia::render('reports', [
            'salesTotal' => $salesTotal,
            'profitTotal' => $profitTotal,
            'utangBalance' => $utangBalance,
            'expenseTotal' => $expenseTotal,
        ]);
    }
}
