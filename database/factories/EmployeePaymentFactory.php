<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeePaymentFactory extends Factory
{
    protected $model = EmployeePayment::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'amount' => fake()->numberBetween(500, 5000),
            'payment_date' => fake()->dateTimeBetween('-90 days', 'now'),
            'description' => fake()->sentence(),
        ];
    }
}
