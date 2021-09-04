<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostsBookmarkController extends Controller
{
    public function index()
    {
        return response()->json(auth()->user()->bookmarks);
    }

    public function store(Post $post): JsonResponse
    {
        auth()->user()->bookmark($post);

        return response()->json([
            'message' => 'Post has been bookmarked successfully',
            'data' => $post
        ], 201);
    }
}
