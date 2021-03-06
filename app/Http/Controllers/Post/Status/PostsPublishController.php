<?php

namespace App\Http\Controllers\Post\Status;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

class PostsPublishController extends Controller
{
    public function __invoke(Post $post): JsonResponse
    {
        if (!auth()->user()->owns($post)) {
            throw new UnauthorizedException();
        }

        $post->publish();

        return response()->json([
            'message' => 'Post published successfully',
            'data' => $post
        ]);
    }
}
