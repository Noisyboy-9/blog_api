<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JetBrains\PhpStorm\ArrayShape;
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

// users
function addNewUser(array $attributes = []): User
{
    return User::factory()->create($attributes);
}

#[ArrayShape(['username' => "string", 'email' => "string", 'password' => "string", 'password_confirmation' => "string"])]
function scaffoldNewUser(array $attributes = []): array
{
    $user = [
        'username' => 'noisyboy-9',
        'email' => 'sina.shariati@yahoo.com',
        'password' => 'admin123',
        'password_confirmation' => 'admin123'
    ];

    foreach ($user as $key => $value) {
        if (array_key_exists($key, $attributes)) {
            $user[$key] = $attributes[$key];
        }
    }

    return $user;
}

