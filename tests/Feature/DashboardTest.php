<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $store = $user->stores()->create([
        'name' => 'Test Store',
        'slug' => 'test-store',
        'currency' => 'PHP',
        'phone' => '09171234567',
        'address' => 'Test address',
        'is_active' => true,
    ]);

    $user->stores()->updateExistingPivot($store, ['role' => 'owner']);
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});
