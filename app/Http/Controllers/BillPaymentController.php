<?php

namespace App\Http\Controllers;

use App\Models\BillPayment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BillPaymentController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $payments = $store->billPayments()
            ->latest('paid_at')
            ->get()
            ->map(fn (BillPayment $payment) => [
                'id' => $payment->id,
                'customerId' => $payment->customer_id,
                'biller' => $payment->biller,
                'accountNumber' => $payment->account_number,
                'accountName' => $payment->account_name,
                'amount' => (int) $payment->amount,
                'serviceFee' => (int) $payment->service_fee,
                'total' => (int) $payment->total,
                'referenceNumber' => $payment->reference_number,
                'status' => $payment->status,
                'paidAt' => $payment->paid_at?->format('Y-m-d H:i:s'),
            ])
            ->values()
            ->all();

        return Inertia::render('bills', [
            'payments' => $payments,
        ]);
    }

    public function store(Request $request)
    {
        $store = $request->user()->stores()->firstOrFail();

        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'biller' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'service_fee' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:0'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:pending,paid,failed,refunded'],
        ]);

        $store->billPayments()->create([
            'customer_id' => $data['customer_id'] ?? null,
            'biller' => $data['biller'],
            'account_number' => $data['account_number'],
            'account_name' => $data['account_name'] ?? null,
            'amount' => $data['amount'],
            'service_fee' => $data['service_fee'],
            'total' => $data['total'],
            'reference_number' => $data['reference_number'] ?? null,
            'status' => $data['status'],
            'paid_at' => now(),
        ]);

        return redirect()->route('bills')->with('success', 'Bill payment recorded successfully.');
    }
}
