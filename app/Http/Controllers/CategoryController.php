<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\BulkImportRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(50)->withQueryString();

        return Inertia::render('Categories', [
            'categories' => $categories,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, Category $category)
    {
        $category->delete();

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function export(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=categories.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Name', 'Description', 'Created At'];

        $callback = function() use($categories, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($categories as $category) {
                $row['ID'] = $category->id;
                $row['Name'] = $category->name;
                $row['Description'] = $category->description;
                $row['Created At'] = $category->created_at;

                fputcsv($file, array($row['ID'], $row['Name'], $row['Description'], $row['Created At']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(BulkImportRequest $request)
    {
        $file = $request->file('file');
        $fileHandle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($fileHandle);

        while (($row = fgetcsv($fileHandle)) !== false) {
            // Assume CSV format: Name, Description
            if (count($row) >= 2) {
                Category::updateOrCreate(
                    ['name' => $row[0]],
                    ['description' => $row[1]]
                );
            }
        }

        fclose($fileHandle);

        return redirect()->back()->with('success', 'Categories imported successfully.');
    }
}
