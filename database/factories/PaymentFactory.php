<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'sale_id' => Sale::factory(),
            'payment_method' => fake()->randomElement(['cash', 'gcash', 'utang']),
            'amount' => fake()->numberBetween(100, 5000),
            'reference_number' => fake()->unique()->numerify('REF-######'),
            'paid_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
