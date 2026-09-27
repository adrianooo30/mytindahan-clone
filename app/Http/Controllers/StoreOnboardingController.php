<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\StoreUpdateRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class StoreOnboardingController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('store/onboarding', [
            'store' => [
                'name' => '',
                'currency' => 'PHP',
                'phone' => '',
                'address' => '',
            ],
        ]);
    }

    public function store(StoreUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->stores()->exists()) {
            return redirect()->route('dashboard');
        }

        $store = $user->stores()->create([
            'name' => $request->validated('name'),
            'slug' => $this->generateUniqueSlug($request->validated('name')),
            'currency' => $request->validated('currency'),
            'phone' => $request->validated('phone'),
            'address' => $request->validated('address'),
            'is_active' => true,
        ]);

        $user->stores()->updateExistingPivot($store, ['role' => 'owner']);

        return redirect()->route('dashboard');
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
