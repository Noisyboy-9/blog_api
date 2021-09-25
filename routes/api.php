<?php


use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Post\Bookmark\PostsBookmarkController;
use App\Http\Controllers\Post\Comment\PostsCommentController;
use App\Http\Controllers\Post\Feed\PostsFeedController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\Status\PostsPublishController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)->except('index');
    Route::prefix('/posts/{post}')->group(function () {
        Route::apiResource('comments', PostsCommentController::class);
        Route::post('/publish', PostsPublishController::class);
        Route::post('/bookmark', [PostsBookmarkController::class, 'store']);
    });
    Route::apiResource('categories', CategoryController::class);

    Route::get('/user/bookmarks', [PostsBookmarkController::class, 'index']);
});

Route::get('feed', PostsFeedController::class);
