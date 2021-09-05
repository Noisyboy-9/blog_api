<?php

namespace App\Http\Controllers\Post\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\posts\comments\PostCommentStoreRequest;
use App\Http\Requests\posts\comments\PostCommentUpdateRequest;
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

    public function destroy(Post $post, Comment $comment): JsonResponse
    {
        if (!auth()->user()->owns($comment)) {
            throw new UnauthorizedException("User doesn't own the comment");
        }

        $comment->delete();
        return response()->json([
            'message' => 'comment deleted successfully',
            'data' => $comment
        ], 204);
    }

    public function update(Post $post, Comment $comment, PostCommentUpdateRequest $updateRequest): JsonResponse
    {
        if (!auth()->user()->owns($comment)) {
            throw new UnauthorizedException("The user doesn't own the comment.");
        }

        $comment->update($updateRequest->validated());

        return response()->json([
            'message' => 'Comment has been updated successfully',
            'data' => $comment
        ]);
    }

    public function index(Post $post): JsonResponse
    {
        return response()->json($post->comments);
    }
}
