<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can create a store when none exists', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'My First Store',
            'currency' => 'PHP',
            'phone' => '09181234567',
            'address' => 'Main Street',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('store.edit'));

    $this->assertDatabaseHas('stores', [
        'name' => 'My First Store',
        'currency' => 'PHP',
        'phone' => '09181234567',
        'address' => 'Main Street',
    ]);

    expect($user->stores()->count())->toBe(1)
        ->and($user->stores()->first()->name)->toBe('My First Store');
});

test('store settings can be updated', function () {
    $user = User::factory()->create();
    $store = $user->stores()->create([
        'name' => 'Sample Store',
        'slug' => 'sample-store',
        'currency' => 'PHP',
        'phone' => '09171234567',
        'address' => 'Old address',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Updated Store',
            'currency' => 'PHP',
            'phone' => '09181234567',
            'address' => 'New store address',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('store.edit'));

    $store->refresh();

    expect($store->name)->toBe('Updated Store')
        ->and($store->phone)->toBe('09181234567')
        ->and($store->address)->toBe('New store address');
});

test('user without a store is redirected to onboarding', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response->assertRedirect(route('store.onboarding'));
});

test('user can create a store from onboarding', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('store.onboarding.store'), [
            'name' => 'Onboarded Store',
            'currency' => 'PHP',
            'phone' => '09181234567',
            'address' => 'New onboarding address',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('stores', [
        'name' => 'Onboarded Store',
        'currency' => 'PHP',
        'phone' => '09181234567',
        'address' => 'New onboarding address',
    ]);

    expect($user->stores()->count())->toBe(1)
        ->and($user->stores()->first()->name)->toBe('Onboarded Store');
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('profile.destroy'), [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('profile.edit'))
        ->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect(route('profile.edit'));

    expect($user->fresh())->not->toBeNull();
});
