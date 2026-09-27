<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'category' => fake()->randomElement(['Electricity', 'Rent', 'Supplies', 'Transportation', 'Miscellaneous']),
            'description' => fake()->sentence(),
            'amount' => fake()->numberBetween(200, 10000),
            'expense_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'created_by' => User::factory(),
        ];
    }
}
