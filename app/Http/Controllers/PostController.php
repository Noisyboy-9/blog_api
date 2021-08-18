<?php

namespace App\Http\Controllers;

use App\Http\Requests\posts\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function store(PostStoreRequest $storeRequest): JsonResponse
    {
        $data = $storeRequest->validated();

        Post::create($data);

        return response()->json([
            'message' => 'Post created successfully!',
            'data' => $data
        ], 201);
    }
}
