<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerTransactionFactory extends Factory
{
    protected $model = CustomerTransaction::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(100, 3000);

        return [
            'store_id' => Store::factory(),
            'customer_id' => Customer::factory(),
            'sale_id' => null,
            'type' => fake()->randomElement(['debt', 'payment', 'adjustment']),
            'amount' => $amount,
            'balance_after' => $amount,
            'description' => fake()->sentence(),
            'transaction_date' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
