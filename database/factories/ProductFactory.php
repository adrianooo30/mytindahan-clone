<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $cost = fake()->numberBetween(50, 5000);

        return [
            'store_id' => Store::factory(),
            'category_id' => ProductCategory::factory(),
            'supplier_id' => Supplier::factory(),
            'name' => fake()->unique()->words(2, true),
            'sku' => strtoupper(fake()->bothify('SKU-####??')),
            'barcode' => fake()->ean13(),
            'description' => fake()->sentence(),
            'cost_price' => $cost,
            'selling_price' => max($cost + 100, (int) round($cost * 1.35)),
            'unit' => fake()->randomElement(['pcs', 'pack', 'bottle', 'box']),
            'reorder_level' => fake()->numberBetween(2, 20),
            'is_active' => true,
        ];
    }
}
