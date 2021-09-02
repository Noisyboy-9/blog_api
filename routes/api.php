<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\posts\PostsCommentController;
use App\Http\Controllers\posts\PostsPublishController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::prefix('/posts/{post}')->group(function () {
        Route::apiResource('comments', PostsCommentController::class);
        Route::post('/publish', PostsPublishController::class);
    });
    Route::apiResource('categories', CategoryController::class);
});

