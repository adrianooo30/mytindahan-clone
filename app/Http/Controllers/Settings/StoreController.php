<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StoreUpdateRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    /**
     * Show the store settings page.
     */
    public function edit(Request $request): Response
    {
        $store = $request->user()?->stores()->first();

        return Inertia::render('settings/store', [
            'store' => [
                'id' => $store?->id,
                'name' => $store?->name ?? '',
                'currency' => $store?->currency ?? 'PHP',
                'phone' => $store?->phone,
                'address' => $store?->address,
            ],
        ]);
    }

    /**
     * Update the store settings.
     */
    public function update(StoreUpdateRequest $request): RedirectResponse
    {
        $store = $request->user()?->stores()->first();

        if (! $store) {
            $store = $request->user()->stores()->create([
                'name' => $request->validated('name'),
                'slug' => $this->generateUniqueSlug($request->validated('name')),
                'currency' => $request->validated('currency'),
                'phone' => $request->validated('phone'),
                'address' => $request->validated('address'),
                'is_active' => true,
            ]);
        } else {
            $store->update($request->validated());
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Store settings updated.')]);

        return to_route('store.edit');
    }

    protected function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'store';
        $slug = $base;
        $counter = 1;

        while (Store::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
