<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\EloadTransaction;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloadTransactionFactory extends Factory
{
    protected $model = EloadTransaction::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'customer_id' => Customer::factory(),
            'mobile_number' => fake()->numerify('09#########'),
            'network' => fake()->randomElement(['Globe', 'Smart', 'Dito']),
            'product_name' => fake()->randomElement(['Load 100', 'Load 300', 'Load 500']),
            'amount' => fake()->numberBetween(100, 5000),
            'reference_number' => fake()->unique()->numerify('ELOAD-######'),
            'status' => fake()->randomElement(['pending', 'successful', 'failed', 'refunded']),
            'sold_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
