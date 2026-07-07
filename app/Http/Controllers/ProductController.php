<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->get();

        if ($request->wantsJson()) {
            return response()->json($products);
        }

        return Inertia::render('Products', [
            'products' => $products,
            'categories' => \App\Models\Category::all()
        ]);
    }

    public function store(Request $request)
    {
        if ($request->has('stock_level')) {
            $request->merge(['stock_quantity' => $request->stock_level]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('products')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                })
            ],
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'cost' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $productData = $request->except(['image', 'stock_level']);
        $productData['image_path'] = $imagePath;
        $productData['company_id'] = auth()->user()->company_id;

        $product = Product::create($productData);
        $product->load('category');

        if ($request->wantsJson()) {
            return response()->json($product, 201);
        }

        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        if ($request->has('stock_level')) {
            $request->merge(['stock_quantity' => $request->stock_level]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('products')->where(function ($query) use ($product) {
                    return $query->where('company_id', auth()->user()->company_id)->ignore($product->id);
                })
            ],
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'cost' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update($request->all());
        $product->load('category');

        if ($request->wantsJson()) {
            return response()->json($product);
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
