<?php

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

// custom expects
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeInvalid', function () {
    return $this->toEqual(302);
});

//helpers

//categories
function addNewCategory(array $attributes = []): Category
{
    return Category::factory()->create($attributes);
}

function scaffoldNewCategory(array $attributes = []): array
{
    return Category::factory()
        ->make($attributes)
        ->toArray();
}

// posts
function addNewPost(array $attributes = []): Post
{
    return Post::factory()->create($attributes);
}

function scaffoldNewPost(array $attributes = []): array
{
    return Post::factory()
        ->make($attributes)
        ->toArray();
}
