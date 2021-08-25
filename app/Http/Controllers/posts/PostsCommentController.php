<?php

namespace App\Http\Controllers\posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\posts\comments\PostCommentStoreRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class PostsCommentController extends Controller
{
    public function store(Post $post, PostCommentStoreRequest $storeRequest): JsonResponse
    {
        $attributes = $storeRequest->validated();
        $attributes['owner_id'] = auth()->id();

        $comment = $post->comments()->create($attributes);

        return response()->json([
            'message' => 'Commented successfully',
            'data' => $comment,
        ], 201);
    }

    public function delete(Post $post, Comment $comment): JsonResponse
    {
        if (!auth()->user()->is($comment->owner)) {
            throw new UnauthorizedException("User doesn't own the comment");
        }
        $comment->delete();
        return response()->json([
            'message' => 'comment deleted successfully',
            'data' => $comment
        ], 204);
    }
}
