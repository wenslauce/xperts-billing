<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('pricing')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:shared_hosting,reseller,vps,domain',
            'description' => 'nullable|string',
            'directadmin_package' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'pricing' => 'required|array|min:1',
            'pricing.*.billing_cycle' => 'required|string|in:monthly,quarterly,semiannual,annual,biennial',
            'pricing.*.price' => 'required|numeric|min:0',
            'pricing.*.setup_fee' => 'nullable|numeric|min:0',
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'directadmin_package' => $validated['directadmin_package'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach ($validated['pricing'] as $price) {
            $product->pricing()->create([
                'billing_cycle' => $price['billing_cycle'],
                'price' => $price['price'],
                'setup_fee' => $price['setup_fee'] ?? 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('pricing');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:shared_hosting,reseller,vps,domain',
            'description' => 'nullable|string',
            'directadmin_package' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'pricing' => 'required|array|min:1',
            'pricing.*.billing_cycle' => 'required|string|in:monthly,quarterly,semiannual,annual,biennial',
            'pricing.*.price' => 'required|numeric|min:0',
            'pricing.*.setup_fee' => 'nullable|numeric|min:0',
        ]);

        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'directadmin_package' => $validated['directadmin_package'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $product->pricing()->delete();
        foreach ($validated['pricing'] as $price) {
            $product->pricing()->create([
                'billing_cycle' => $price['billing_cycle'],
                'price' => $price['price'],
                'setup_fee' => $price['setup_fee'] ?? 0,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}