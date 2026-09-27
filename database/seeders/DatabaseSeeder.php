<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $store = Store::factory()->create([
            'name' => 'Nayon Sari-Sari',
            'slug' => 'nayon-sari-sari',
            'currency' => 'PHP',
        ]);

        $store->users()->attach($user, ['role' => 'owner']);

        $categories = ProductCategory::factory()->count(5)->create([
            'store_id' => $store->id,
        ]);

        $supplier = Supplier::factory()->create([
            'store_id' => $store->id,
            'name' => 'Prime Wholesale Supply',
        ]);

        $products = Product::factory()->count(12)->create([
            'store_id' => $store->id,
            'category_id' => $categories->random()->id,
            'supplier_id' => $supplier->id,
        ]);

        foreach ($products as $product) {
            $quantity = fake()->numberBetween(10, 120);

            $product->inventoryBatches()->create([
                'store_id' => $store->id,
                'batch_number' => 'B-'.fake()->unique()->numerify('####'),
                'quantity' => $quantity,
                'remaining_quantity' => $quantity,
                'cost_price' => $product->cost_price,
                'manufactured_at' => now()->subWeeks(2)->toDateString(),
                'expires_at' => now()->addMonths(6)->toDateString(),
            ]);
        }

        Customer::factory()->count(8)->create([
            'store_id' => $store->id,
        ]);

        Employee::factory()->count(4)->create([
            'store_id' => $store->id,
        ]);

        Expense::factory()->count(5)->create([
            'store_id' => $store->id,
            'created_by' => $user->id,
        ]);
    }
}
