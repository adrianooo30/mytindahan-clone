<?php

namespace Database\Factories;

use App\Models\CashTransaction;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashTransactionFactory extends Factory
{
    protected $model = CashTransaction::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'type' => fake()->randomElement(['cash_in', 'cash_out', 'adjustment']),
            'amount' => fake()->numberBetween(100, 5000),
            'reference_number' => fake()->unique()->numerify('CASH-######'),
            'description' => fake()->sentence(),
            'transaction_date' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
