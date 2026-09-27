<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'position' => fake()->randomElement(['Cashier', 'Stock Clerk', 'Manager', 'Sales Associate']),
            'salary_type' => fake()->randomElement(['daily', 'weekly', 'monthly']),
            'salary_amount' => fake()->numberBetween(1500, 50000),
            'is_active' => true,
        ];
    }
}
