<?php

namespace App\Http\Controllers\Post\Bookmark;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostsBookmarkController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            auth()->user()->bookmarks()
        );
    }

    public function store(Post $post): JsonResponse
    {
        if (auth()->user()->hasBookmark($post)) {
            return response()->json([
                'message' => 'Post has been already bookmarked',
            ], 403);
        }

        auth()->user()->bookmark($post);

        return response()->json([
            'message' => 'Post has been bookmarked successfully',
            'data' => $post
        ], 201);
    }
}
