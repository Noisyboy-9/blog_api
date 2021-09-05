<?php

namespace App\Http\Controllers\Post;

use App\blog_api\posts\PostStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function store(PostStoreRequest $storeRequest): JsonResponse
    {
        $attributes = $storeRequest->validated();
        $attributes['status'] ??= PostStatusEnum::DRAFT;
        $post = auth()->user()->posts()->create($attributes);

        return response()->json([
            'message' => 'Post created successfully!',
            'data' => $post
        ], 201);
    }

    public function show(Post $post): JsonResponse
    {

        $post->addViewIfNotExist(auth()->user());
        $post->view_count = $post->viewsCount();

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
