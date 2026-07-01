<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        // Eager load the category name for the frontend
        return response()->json(Product::with('category')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 
                'string', 
                'max:255', 
                // Ensure SKU is unique within this specific company
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('company_id', auth()->user()->company_id);
                })
            ],
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'cost' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0',
        ]);

        $product = Product::create($request->all());
        $product->load('category');

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('company_id', auth()->user()->company_id);
                })->ignore($product->id)
            ],
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'cost' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0',
        ]);

        $product->update($request->all());
        $product->load('category');

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}
