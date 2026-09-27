<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(500, 20000);
        $discount = fake()->numberBetween(0, 500);
        $tax = fake()->numberBetween(0, 800);
        $total = $subtotal + $tax - $discount;

        return [
            'store_id' => Store::factory(),
            'customer_id' => Customer::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('######'),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'payment_status' => fake()->randomElement(['paid', 'partial', 'pending']),
            'sold_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'created_by' => User::factory(),
        ];
    }
}
