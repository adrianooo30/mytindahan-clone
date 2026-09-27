<?php

use App\Models\Customer;
use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sale;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('a store can own users and product records', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();

    $store->users()->attach($user);

    expect($store->users()->count())->toBe(1)
        ->and($store->users()->first()->id)->toBe($user->id)
        ->and($store->products()->count())->toBe(0);
});

test('inventory and sales records can be related through a product', function () {
    $store = Store::factory()->create();
    $category = ProductCategory::factory()->for($store)->create();
    $supplier = Supplier::factory()->for($store)->create();
    $product = Product::factory()->for($store)->for($category)->for($supplier)->create([
        'cost_price' => 1200,
        'selling_price' => 2500,
    ]);

    $product->inventoryBatches()->create([
        'store_id' => $store->id,
        'batch_number' => 'B-001',
        'quantity' => 10,
        'remaining_quantity' => 10,
        'cost_price' => 1200,
    ]);

    $customer = Customer::factory()->for($store)->create();
    $sale = Sale::factory()->for($store)->for($customer)->create([
        'subtotal' => 2500,
        'discount' => 0,
        'tax' => 0,
        'total' => 2500,
    ]);

    $sale->items()->create([
        'store_id' => $store->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => 2500,
        'cost_price' => 1200,
        'discount' => 0,
        'subtotal' => 2500,
    ]);

    expect($product->inventoryBatches()->count())->toBe(1)
        ->and($sale->items()->count())->toBe(1)
        ->and($customer->sales()->count())->toBe(1)
        ->and($product->category->id)->toBe($category->id);
});

test('an authenticated store owner can create a product for their store', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $response = $this->actingAs($user)->post('/products', [
        'name' => 'Rice 5kg',
        'sku' => 'RICE-001',
        'cost_price' => 1200,
        'selling_price' => 1500,
        'unit' => 'pack',
    ]);

    $response->assertRedirect('/products');

    $this->assertDatabaseHas('products', [
        'store_id' => $store->id,
        'name' => 'Rice 5kg',
        'sku' => 'RICE-001',
        'cost_price' => 1200,
        'selling_price' => 1500,
    ]);
});

test('authenticated store owners can view real dashboard and inventory summaries', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $product = Product::factory()->for($store)->create([
        'name' => 'Pancit Canton',
        'cost_price' => 1800,
        'selling_price' => 2400,
    ]);

    $product->inventoryBatches()->create([
        'store_id' => $store->id,
        'batch_number' => 'B-100',
        'quantity' => 12,
        'remaining_quantity' => 4,
        'cost_price' => 1800,
    ]);

    $sale = Sale::factory()->for($store)->create([
        'subtotal' => 2400,
        'discount' => 0,
        'tax' => 0,
        'total' => 2400,
        'sold_at' => now(),
    ]);

    $customer = Customer::factory()->for($store)->create();
    $customer->transactions()->create([
        'store_id' => $store->id,
        'sale_id' => $sale->id,
        'type' => 'debt',
        'amount' => 500,
        'balance_after' => 500,
        'description' => 'Outstanding balance',
        'transaction_date' => now()->toDateString(),
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('dashboard')
            ->where('todaySales', 2400)
            ->where('transactionCount', 1)
            ->where('inventoryValue', 7200)
            ->where('outstandingUtang', 500)
        );

    $this->actingAs($user)
        ->get('/inventory')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('inventory')
            ->where('totalSkus', 1)
            ->where('criticalItems', 1)
            ->where('inventoryValue', 7200)
        );
});

test('an authenticated store owner can record a POS sale and reduce stock', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $product = Product::factory()->for($store)->create([
        'name' => 'Coke 1L',
        'cost_price' => 2500,
        'selling_price' => 3500,
    ]);

    $product->inventoryBatches()->create([
        'store_id' => $store->id,
        'batch_number' => 'POS-001',
        'quantity' => 10,
        'remaining_quantity' => 10,
        'cost_price' => 2500,
    ]);

    $response = $this->actingAs($user)->post('/pos', [
        'items' => [[
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 3500,
        ]],
        'discount' => 0,
        'payment_method' => 'cash',
        'amount_paid' => 7000,
    ]);

    $response->assertRedirect('/pos');

    $this->assertDatabaseHas('sales', [
        'store_id' => $store->id,
        'total' => 7000,
        'payment_status' => 'paid',
    ]);

    $this->assertDatabaseHas('sale_items', [
        'product_id' => $product->id,
        'quantity' => 2,
        'subtotal' => 7000,
    ]);

    $product->refresh();
    $this->assertEquals(8, $product->inventoryBatches()->sum('remaining_quantity'));
});

test('an authenticated store owner can create an utang sale and record the customer balance', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $customer = Customer::factory()->for($store)->create([
        'name' => 'Juan Dela Cruz',
        'phone' => '09171234567',
    ]);

    $product = Product::factory()->for($store)->create([
        'name' => 'Ligo Soap',
        'cost_price' => 1200,
        'selling_price' => 1800,
    ]);

    $product->inventoryBatches()->create([
        'store_id' => $store->id,
        'batch_number' => 'UTANG-001',
        'quantity' => 5,
        'remaining_quantity' => 5,
        'cost_price' => 1200,
    ]);

    $response = $this->actingAs($user)->post('/pos', [
        'customer_id' => $customer->id,
        'items' => [[
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 1800,
        ]],
        'discount' => 0,
        'payment_method' => 'utang',
        'amount_paid' => 0,
    ]);

    $response->assertRedirect('/pos');

    $this->assertDatabaseHas('sales', [
        'store_id' => $store->id,
        'customer_id' => $customer->id,
        'total' => 3600,
        'payment_status' => 'pending',
    ]);

    $this->assertDatabaseHas('customer_transactions', [
        'store_id' => $store->id,
        'customer_id' => $customer->id,
        'sale_id' => $store->sales()->latest()->first()->id,
        'type' => 'debt',
        'amount' => 3600,
        'balance_after' => 3600,
    ]);

    $customer->refresh();
    $this->assertEquals(3600, $customer->transactions()->sum('amount'));
});

test('an authenticated store owner can record a GCash sale and log the wallet ledger', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $product = Product::factory()->for($store)->create([
        'name' => 'Tissue Pack',
        'cost_price' => 1500,
        'selling_price' => 2200,
    ]);

    $product->inventoryBatches()->create([
        'store_id' => $store->id,
        'batch_number' => 'GCASH-001',
        'quantity' => 4,
        'remaining_quantity' => 4,
        'cost_price' => 1500,
    ]);

    $response = $this->actingAs($user)->post('/pos', [
        'items' => [[
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 2200,
        ]],
        'discount' => 0,
        'payment_method' => 'gcash',
        'amount_paid' => 2200,
    ]);

    $response->assertRedirect('/pos');

    $this->assertDatabaseHas('payments', [
        'store_id' => $store->id,
        'payment_method' => 'gcash',
        'amount' => 2200,
    ]);

    $this->assertDatabaseHas('gcash_transactions', [
        'store_id' => $store->id,
        'type' => 'customer_payment',
        'amount' => 2200,
    ]);
});

test('an authenticated store owner can register an employee and record payroll payment', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $response = $this->actingAs($user)->post('/employees', [
        'name' => 'Ana Santos',
        'phone' => '09181234567',
        'position' => 'Cashier',
        'salary_type' => 'monthly',
        'salary_amount' => 18000,
    ]);

    $response->assertRedirect('/employees');

    $this->assertDatabaseHas('employees', [
        'store_id' => $store->id,
        'name' => 'Ana Santos',
        'position' => 'Cashier',
        'salary_amount' => 18000,
    ]);

    $employee = $store->employees()->first();

    $this->actingAs($user)->post('/employees/'.$employee->id.'/payments', [
        'amount' => 6000,
        'description' => 'Advance salary',
    ]);

    $this->assertDatabaseHas('employee_payments', [
        'employee_id' => $employee->id,
        'amount' => 6000,
        'description' => 'Advance salary',
    ]);
});

test('an authenticated store owner can record an expense for the store', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $response = $this->actingAs($user)->post('/expenses', [
        'category' => 'utilities',
        'description' => 'Electric bill',
        'amount' => 2750,
        'expense_date' => now()->toDateString(),
    ]);

    $response->assertRedirect('/expenses');

    $this->assertDatabaseHas('expenses', [
        'store_id' => $store->id,
        'category' => 'utilities',
        'description' => 'Electric bill',
        'amount' => 2750,
    ]);
});

test('an authenticated store owner can record an e-load transaction for the store', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $customer = Customer::factory()->for($store)->create();

    $response = $this->actingAs($user)->post('/eload', [
        'customer_id' => $customer->id,
        'mobile_number' => '09171234567',
        'network' => 'Globe',
        'product_name' => '1GB 7-Day',
        'amount' => 1500,
        'reference_number' => 'ELOAD-001',
        'status' => 'successful',
    ]);

    $response->assertRedirect('/eload');

    $this->assertDatabaseHas('eload_transactions', [
        'store_id' => $store->id,
        'customer_id' => $customer->id,
        'mobile_number' => '09171234567',
        'network' => 'Globe',
        'product_name' => '1GB 7-Day',
        'amount' => 1500,
        'reference_number' => 'ELOAD-001',
        'status' => 'successful',
    ]);
});

test('an authenticated store owner can record a bill payment for the store', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $customer = Customer::factory()->for($store)->create();

    $response = $this->actingAs($user)->post('/bills', [
        'customer_id' => $customer->id,
        'biller' => 'MERALCO',
        'account_number' => '1234567890',
        'account_name' => 'Juan Dela Cruz',
        'amount' => 1500,
        'service_fee' => 50,
        'total' => 1550,
        'reference_number' => 'BILL-001',
        'status' => 'paid',
    ]);

    $response->assertRedirect('/bills');

    $this->assertDatabaseHas('bill_payments', [
        'store_id' => $store->id,
        'customer_id' => $customer->id,
        'biller' => 'MERALCO',
        'account_number' => '1234567890',
        'account_name' => 'Juan Dela Cruz',
        'amount' => 1500,
        'service_fee' => 50,
        'total' => 1550,
        'reference_number' => 'BILL-001',
        'status' => 'paid',
    ]);
});

test('authenticated store owners can view low stock and expiration alerts for their store', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $product = Product::factory()->for($store)->create([
        'name' => 'Instant Noodles',
        'reorder_level' => 5,
    ]);

    $product->inventoryBatches()->create([
        'store_id' => $store->id,
        'batch_number' => 'ALERT-001',
        'quantity' => 10,
        'remaining_quantity' => 2,
        'cost_price' => 1600,
        'expires_at' => now()->addDays(4),
    ]);

    $this->actingAs($user)
        ->get('/notifications')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('notifications')
            ->where('alerts.0.type', 'low_stock')
            ->where('alerts.0.title', 'Low stock: Instant Noodles')
            ->where('alerts.1.type', 'expiring_soon')
            ->where('alerts.1.title', 'Expiring soon: Instant Noodles')
        );
});

test('authenticated store owners can view real reports data for the store', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create();
    $user->stores()->attach($store, ['role' => 'owner']);

    $customer = Customer::factory()->for($store)->create();

    $sale = Sale::factory()->for($store)->for($customer)->create([
        'subtotal' => 2000,
        'discount' => 0,
        'tax' => 0,
        'total' => 2000,
        'payment_status' => 'paid',
        'sold_at' => now(),
    ]);

    $sale->items()->create([
        'store_id' => $store->id,
        'product_id' => Product::factory()->for($store)->create([
            'cost_price' => 1200,
            'selling_price' => 2000,
        ])->id,
        'quantity' => 1,
        'unit_price' => 2000,
        'cost_price' => 1200,
        'discount' => 0,
        'subtotal' => 2000,
    ]);

    $store->expenses()->create([
        'category' => 'utilities',
        'description' => 'Power bill',
        'amount' => 300,
        'expense_date' => now()->toDateString(),
        'created_by' => $user->id,
    ]);

    $customer->transactions()->create([
        'store_id' => $store->id,
        'sale_id' => $sale->id,
        'type' => 'debt',
        'amount' => 500,
        'balance_after' => 500,
        'description' => 'Outstanding balance',
        'transaction_date' => now()->toDateString(),
    ]);

    $this->actingAs($user)
        ->get('/reports')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('reports')
            ->where('salesTotal', 2000)
            ->where('profitTotal', 800)
            ->where('utangBalance', 500)
            ->where('expenseTotal', 300)
        );
});
