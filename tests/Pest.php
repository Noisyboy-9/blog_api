<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
//authentication
function signIn(User $user = null): Model|Collection|User
{
    $user = $user ?? User::factory()->create();
    test()->actingAs($user);
    return $user;
}

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
