<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->numberBetween(100, 1000);
        $costPrice = fake()->numberBetween(50, 800);
        $discount = fake()->numberBetween(0, 100);
        $subtotal = ($unitPrice * $quantity) - $discount;

        return [
            'store_id' => Store::factory(),
            'sale_id' => Sale::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'cost_price' => $costPrice,
            'discount' => $discount,
            'subtotal' => $subtotal,
        ];
    }
}
