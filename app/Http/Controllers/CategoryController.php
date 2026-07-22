<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories()
            ->withCount('expenses')
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'status' => ['required', 'boolean'],
        ]);

        $category = auth()->user()->categories()->create($data);

        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = auth()->user()->categories()->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'status' => ['required', 'boolean'],
        ]);

        $category->update($data);

        return response()->json([
            'message' => 'Category updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $category = auth()->user()->categories()->findOrFail($id);

        if ($category->expenses()->exists()) {
            return response()->json([
                'message' => 'This category cannot be deleted because it has expenses.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
