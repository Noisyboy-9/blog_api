<?php

namespace App\Http\Controllers;

use App\Http\Requests\posts\PostStoreRequest;
use App\Http\Requests\posts\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private array $filters = ['category', 'search'];

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Post::latest()->filter(request()->only(...$this->filters))->get()
        ]);
    }

    public function store(PostStoreRequest $storeRequest): JsonResponse
    {
        $attributes = $storeRequest->validated();

        $post = auth()->user()->posts()->create($attributes);


        return response()->json([
            'message' => 'Post created successfully!',
            'data' => $post
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
        $attributes = $updateRequest->validated();

        $post->update($attributes);

        return response()->json([
            'message' => 'post updated successfully',
            'data' => $post->toArray()
        ], 200);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'post has been delete successfully',
            'data' => $post->toArray(),
        ], 204);
    }
}
