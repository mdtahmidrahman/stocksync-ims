<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($products);
        }

        return Inertia::render('Products', [
            'products' => $products,
            'categories' => Category::all(),
            'filters' => $request->only(['search', 'category_id'])
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
            'sku' =>
            [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('products')->ignore($product->id)->where(function ($query) {
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

        $product->update($request->all());
        $product->load('category');

        if ($request->wantsJson()) {
            return response()->json($product);
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=products.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Name', 'SKU', 'Category', 'Price', 'Stock Level', 'Created At'];

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $product) {
                $row['ID'] = $product->id;
                $row['Name'] = $product->name;
                $row['SKU'] = $product->sku;
                $row['Category'] = $product->category ? $product->category->name : 'Uncategorized';
                $row['Price'] = $product->price;
                $row['Stock Level'] = $product->stock_quantity;
                $row['Created At'] = $product->created_at;

                fputcsv($file, array($row['ID'], $row['Name'], $row['SKU'], $row['Category'], $row['Price'], $row['Stock Level'], $row['Created At']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $fileHandle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($fileHandle);

        while (($row = fgetcsv($fileHandle)) !== false) {
            // Assume CSV format: Name, SKU, Category ID, Price, Stock Level
            if (count($row) >= 5) {
                Product::updateOrCreate(
                    ['sku' => $row[1]], // Update by SKU
                    [
                        'name' => $row[0],
                        'category_id' => $row[2] ?: null,
                        'price' => is_numeric($row[3]) ? $row[3] : 0,
                        'stock_quantity' => is_numeric($row[4]) ? $row[4] : 0,
                    ]
                );
            }
        }

        fclose($fileHandle);

        return redirect()->back()->with('success', 'Products imported successfully.');
    }
}
