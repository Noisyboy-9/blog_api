<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::all());
    }

    public function store(CategoryStoreRequest $storeRequest): JsonResponse
    {
        $attributes = $storeRequest->validated();

        $category = Category::create($attributes);

        return response()->json([
            'message' => 'Category has been created successfully',
            'data' => $category->toArray()
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category->posts);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
            'data' => $category
        ], 204);
    }
}
