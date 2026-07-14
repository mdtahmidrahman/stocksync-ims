<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use ZipArchive;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\BulkImportRequest;

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

        $products = $query->latest()->paginate(50)->withQueryString();

        return Inertia::render('Products', [
            'products' => $products,
            'categories' => Category::all(),
            'filters' => $request->only(['search', 'category_id'])
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        if ($request->has('stock_level')) {
            $request->merge(['stock_quantity' => $request->stock_level]);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $productData = $request->except(['image', 'stock_level']);
        $productData['image_path'] = $imagePath;
        $productData['company_id'] = auth()->user()->company_id;

        $product = Product::create($productData);
        $product->load('category');

        $product->load('category');
        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->has('stock_level')) {
            $request->merge(['stock_quantity' => $request->stock_level]);
        }

        $product->update($request->all());
        $product->load('category');

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

        $format = $request->query('format', 'xlsx');
        $filename = 'products.' . ($format === 'csv' ? 'csv' : 'xlsx');

        return (new FastExcel($products))->download($filename, function ($product) {
            return [
                'ID' => $product->id,
                'Name' => $product->name,
                'SKU' => $product->sku,
                'Category' => $product->category ? $product->category->name : 'Uncategorized',
                'Price' => $product->price,
                'Stock Level' => $product->stock_quantity,
                'Image URL' => $product->image_path ? url('storage/' . $product->image_path) : '',
                'Created At' => $product->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function import(BulkImportRequest $request)
    {
        // Allowing the script to run as long as necessary for heavy bulk uploads and image downloading
        set_time_limit(0);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $spreadsheetPath = null;
        $extractPath = null;

        // Handle ZIP Uploads
        if ($extension === 'zip') {
            $zip = new ZipArchive;
            if ($zip->open($file->getPathname()) === TRUE) {
                $extractPath = storage_path('app/temp/import_' . Str::random(10));
                if (!File::exists($extractPath)) {
                    File::makeDirectory($extractPath, 0755, true);
                }
                $zip->extractTo($extractPath);
                $zip->close();

                // Finds the spreadsheet inside the extracted ZIP
                $files = File::allFiles($extractPath);
                foreach ($files as $f) {
                    if (in_array($f->getExtension(), ['csv', 'xlsx', 'xls'])) {
                        $spreadsheetPath = $f->getPathname();
                        break;
                    }
                }

                if (!$spreadsheetPath) {
                    File::deleteDirectory($extractPath);
                    return redirect()->back()->with('error', 'No valid spreadsheet (.csv, .xlsx) found inside the ZIP file.');
                }
            } else {
                return redirect()->back()->with('error', 'Failed to open ZIP file.');
            }
        } else {
            $spreadsheetPath = $file->getPathname();
        }

        try {
            $collection = (new FastExcel)->import($spreadsheetPath);
        } catch (\Exception $e) {
            if ($extractPath) File::deleteDirectory($extractPath);
            return redirect()->back()->with('error', 'Failed to parse spreadsheet.');
        }

        foreach ($collection as $row) {
            // Case-insensitive key lookup helper
            $getValue = function($keys) use ($row) {
                foreach ((array)$keys as $k) {
                    foreach ($row as $rowKey => $rowValue) {
                        if (strtolower(trim($rowKey)) === strtolower(trim($k))) {
                            return trim($rowValue);
                        }
                    }
                }
                return null;
            };

            $sku = $getValue('sku');
            $name = $getValue('name');

            if (!$sku || !$name) continue;

            $categoryName = $getValue('category');
            $categoryId = null;
            if ($categoryName && strtolower($categoryName) !== 'uncategorized') {
                $category = Category::firstOrCreate([
                    'name' => $categoryName,
                    'company_id' => auth()->user()->company_id
                ]);
                $categoryId = $category->id;
            }

            $price = $getValue('price');
            $stock = $getValue(['stock level', 'stock_quantity', 'stock']);
            $imageRef = $getValue(['image url', 'image', 'image_url', 'image_path']);

            $product = Product::updateOrCreate(
                [
                    'sku' => $sku,
                    'company_id' => auth()->user()->company_id
                ],
                [
                    'name' => $name,
                    'category_id' => $categoryId,
                    'price' => is_numeric($price) ? $price : 0,
                    'stock_quantity' => is_numeric($stock) ? $stock : 0,
                ]
            );

            // Handle Image Processing
            if ($imageRef) {
                $imagePath = null;

                // Remote URL
                if (filter_var($imageRef, FILTER_VALIDATE_URL)) {
                    // Checking if it's our own app's URL to prevent deadlock on 'php artisan serve'
                    $appUrl = url('/');
                    if (str_starts_with($imageRef, $appUrl)) {
                        // Extracting the relative path (e.g., 'products/image.jpg')
                        $relativePath = str_replace($appUrl . '/storage/', '', $imageRef);
                        if (Storage::disk('public')->exists($relativePath)) {
                            // Reusing it when image already exists on our server
                            $imagePath = $relativePath;
                        }
                    } else {
                        // Downloading when it's true external URL
                        try {
                            $contents = Http::timeout(10)->withoutVerifying()->get($imageRef)->body();
                            $filename = basename(parse_url($imageRef, PHP_URL_PATH));
                            if (!$filename) $filename = Str::random(10) . '.jpg';
                            $path = 'products/' . Str::uuid() . '_' . $filename;
                            Storage::disk('public')->put($path, $contents);
                            $imagePath = $path;
                        } catch (\Exception $e) {
                            // Skip if failed to download
                            \Log::error("Failed to download image from $imageRef: " . $e->getMessage());
                        }
                    }
                } 
                // Local file inside the ZIP
                elseif ($extractPath) {
                    // Try to find the file recursively in the ZIP extraction folder
                    $allFiles = File::allFiles($extractPath);
                    foreach ($allFiles as $f) {
                        if (strtolower($f->getFilename()) === strtolower(basename($imageRef))) {
                            $filename = $f->getFilename();
                            $path = 'products/' . Str::uuid() . '_' . $filename;
                            Storage::disk('public')->put($path, file_get_contents($f->getPathname()));
                            $imagePath = $path;
                            break;
                        }
                    }
                }

                if ($imagePath) {
                    // Delete old image if it exists and is different from the new path
                    if ($product->image_path && $product->image_path !== $imagePath) {
                        Storage::disk('public')->delete($product->image_path);
                    }
                    $product->update(['image_path' => $imagePath]);
                }
            }
        }

        // Cleanup temporary zip extraction directory
        if ($extractPath) {
            File::deleteDirectory($extractPath);
        }

        return redirect()->back()->with('success', 'Products imported successfully.');
    }
}
