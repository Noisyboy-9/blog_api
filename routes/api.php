<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\posts\PostsCommentController;

//posts
Route::resource('posts', PostController::class)
    ->except('create', 'edit')
    ->middleware('auth:sanctum');

//comments
Route::post('/posts/{post}/comments', [PostsCommentController::class, 'store']);

//categories
Route::post('categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::delete('categories/{category}', [CategoryController::class, 'delete'])->middleware('auth:sanctum');

