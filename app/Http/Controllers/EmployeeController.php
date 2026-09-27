<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeePayment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->firstOrFail();

        $employees = $store->employees()->get()->map(function (Employee $employee): array {
            $paid = (int) $employee->payments()->sum('amount');
            $remaining = max(0, (int) $employee->salary_amount - $paid);

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'position' => $employee->position,
                'phone' => $employee->phone,
                'salaryAmount' => (int) $employee->salary_amount,
                'paid' => $paid,
                'remaining' => $remaining,
                'status' => $employee->is_active ? 'Active' : 'Inactive',
            ];
        })->values()->all();

        return Inertia::render('employees', [
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $store = $request->user()->stores()->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['required', 'string', 'max:255'],
            'salary_type' => ['nullable', 'string', 'in:monthly,weekly,daily'],
            'salary_amount' => ['required', 'integer', 'min:0'],
        ]);

        $store->employees()->create([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'position' => $data['position'],
            'salary_type' => $data['salary_type'] ?? 'monthly',
            'salary_amount' => $data['salary_amount'],
            'is_active' => true,
        ]);

        return redirect()->route('employees')->with('success', 'Employee added successfully.');
    }

    public function storePayment(Request $request, Employee $employee)
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $employee->payments()->create([
            'amount' => $request->input('amount'),
            'payment_date' => now()->toDateString(),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('employees')->with('success', 'Payroll payment recorded.');
    }
}
