<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $store = $request->user()?->stores()->first();

        $products = Product::query()
            ->when($store, fn ($query) => $query->where('store_id', $store->id))
            ->with('category')
            ->latest()
            ->get();

        return Inertia::render('products', [
            'products' => $products->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'stock' => $product->inventoryBatches()->sum('remaining_quantity'),
                'price' => $product->selling_price,
                'status' => $product->is_active ? 'Active' : 'Inactive',
            ]),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $store = $request->user()->stores()->firstOrFail();

        $product = Product::create([
            'store_id' => $store->id,
            'category_id' => $request->validated('category_id'),
            'supplier_id' => $request->validated('supplier_id'),
            'name' => $request->validated('name'),
            'sku' => $request->validated('sku'),
            'barcode' => $request->validated('barcode'),
            'description' => $request->validated('description'),
            'cost_price' => $request->validated('cost_price'),
            'selling_price' => $request->validated('selling_price'),
            'unit' => $request->validated('unit', 'pcs'),
            'reorder_level' => $request->validated('reorder_level', 0),
            'is_active' => $request->validated('is_active', true),
        ]);

        return redirect()->route('products')->with('success', 'Product created successfully.');
    }
}
