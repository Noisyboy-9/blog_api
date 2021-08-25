<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\posts\PostsCommentController;

//comments
Route::middleware('auth:sanctum')->group(function () {
//    posts
    Route::resource('posts', PostController::class)->except('create', 'edit');

//    comments
    Route::post('/posts/{post}/comments', [PostsCommentController::class, 'store']);
    Route::delete('/posts/{post}/comments/{comment}', [PostsCommentController::class, 'delete']);
    Route::patch('/posts/{post}/comments/{comment}', [PostsCommentController::class, 'update']);

//    categories
    Route::post('categories', [CategoryController::class, 'store']);
    Route::delete('categories/{category}', [CategoryController::class, 'delete']);
});

//categories
