<?php

namespace App\Http\Controllers\Post\Feed;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostsFeedController extends Controller
{
    private array $filters = ['category', 'search'];

    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => Post::latest()
                ->filter(request()->only(...$this->filters))
                ->published()
                ->get()
        ]);
    }
}
