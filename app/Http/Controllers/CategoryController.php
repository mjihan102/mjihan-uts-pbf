<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Read all categories
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Create a new category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'msg' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    // Read a single category
    public function show(Category $category)
    {
        return response()->json($category);
    }

    // Update an existing category
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $category->update([
            'name' => $request->name ?? $category->name,
        ]);

        return response()->json([
            'msg' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    // Delete a category
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'msg' => 'Category deleted successfully'
        ], 204);
    }
}