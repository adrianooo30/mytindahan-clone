<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $customers = $store->customers()
            ->with('transactions')
            ->get()
            ->map(function (Customer $customer): array {
                $debtTotal = (int) $customer->transactions()->where('type', 'debt')->sum('amount');
                $paymentTotal = (int) $customer->transactions()->where('type', 'payment')->sum('amount');
                $balance = $debtTotal - $paymentTotal;

                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'balance' => $balance,
                    'status' => $balance > 0 ? 'Active' : 'Settled',
                    'creditLimit' => (int) $customer->credit_limit,
                ];
            })
            ->values()
            ->all();

        return Inertia::render('customers', [
            'customers' => $customers,
        ]);
    }
}
