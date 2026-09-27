<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExpenseController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $expenses = $store->expenses()->latest('expense_date')->get()->map(fn (Expense $expense) => [
            'id' => $expense->id,
            'category' => $expense->category,
            'description' => $expense->description,
            'amount' => (int) $expense->amount,
            'expenseDate' => $expense->expense_date?->format('Y-m-d'),
        ])->values()->all();

        return Inertia::render('expenses', [
            'expenses' => $expenses,
        ]);
    }

    public function store(Request $request)
    {
        $store = $request->user()->stores()->firstOrFail();

        $data = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'expense_date' => ['required', 'date'],
        ]);

        $store->expenses()->create([
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('expenses')->with('success', 'Expense recorded successfully.');
    }
}
