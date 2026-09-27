<?php

namespace Database\Factories;

use App\Models\GCashTransaction;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class GCashTransactionFactory extends Factory
{
    protected $model = GCashTransaction::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'type' => fake()->randomElement(['cash_in', 'cash_out', 'customer_payment', 'transfer', 'adjustment']),
            'amount' => fake()->numberBetween(100, 5000),
            'reference_number' => fake()->unique()->numerify('GCASH-######'),
            'description' => fake()->sentence(),
            'transaction_date' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
