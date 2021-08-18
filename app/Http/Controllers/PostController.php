<?php

namespace App\Http\Controllers;

use App\Http\Requests\posts\PostStoreRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function store(PostStoreRequest $storeRequest)
    {
        $data = $storeRequest->validated();

        Post::create($data);

        return response()->json([
            'message' => 'post created successfully!',
            'data' => $data
        ], 201);
    }
}
