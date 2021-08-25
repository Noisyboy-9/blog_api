<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\posts\PostsCommentController;

//comments
Route::middleware('auth:sanctum')->group(function () {
//    posts
    Route::apiResource('posts', PostController::class);

//    comments
    Route::prefix('/posts/{post}')->group(function () {
        Route::apiResource("comments", PostsCommentController::class);
    });

//    categories
    Route::post('categories', [CategoryController::class, 'store']);
    Route::delete('categories/{category}', [CategoryController::class, 'delete']);
});
