<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'name' => fake()->randomElement(['Beverages', 'Snacks', 'Rice & Staples', 'Cleaning', 'Personal Care', 'Household']),
            'description' => fake()->sentence(),
        ];
    }
}
