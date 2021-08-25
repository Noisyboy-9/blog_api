<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\posts\PostsCommentController;

Route::resource('posts', PostController::class)
    ->except('create', 'edit')
    ->middleware('auth:sanctum');

// TODO: implement other routes for the categories resource
Route::post('categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');

Route::post('/posts/{post}/comments', [PostsCommentController::class, 'store']);
