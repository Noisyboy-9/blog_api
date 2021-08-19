<?php

namespace App\Http\Controllers;

use App\Http\Requests\posts\PostStoreRequest;
use App\Http\Requests\posts\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Post::all()->toArray()]);
    }

    public function store(PostStoreRequest $storeRequest): JsonResponse
    {
        $attributes = $storeRequest->validated();

        Post::create($attributes);

        return response()->json([
            'message' => 'Post created successfully!',
            'data' => $attributes
        ], 201);
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'data' => $post->toJson()
        ]);
    }

    public function update(PostUpdateRequest $updateRequest, Post $post): JsonResponse
    {
        $post->update($updateRequest->validated());

        return response()->json([
            'message' => 'post updated successfully',
            'data' => $post->toArray()
        ], 204);
    }
}
