<?php

namespace Database\Factories;

use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryBatchFactory extends Factory
{
    protected $model = InventoryBatch::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(10, 200);

        return [
            'store_id' => Store::factory(),
            'product_id' => Product::factory(),
            'batch_number' => fake()->bothify('B-####'),
            'quantity' => $quantity,
            'remaining_quantity' => $quantity,
            'cost_price' => fake()->numberBetween(50, 3000),
            'manufactured_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'expires_at' => fake()->dateTimeBetween('+1 month', '+1 year'),
        ];
    }
}
