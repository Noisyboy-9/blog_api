<?php

namespace App\Http\Controllers\posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\posts\comments\PostCommentStoreRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostsCommentController extends Controller
{
    public function store(Post $post, PostCommentStoreRequest $storeRequest): JsonResponse
    {
        $attributes = $storeRequest->validated();

        $comment = $post->comments()->create($attributes);

        return response()->json([
            'message' => 'Commented successfully',
            'data' => $comment,
        ], 201);
    }
}
