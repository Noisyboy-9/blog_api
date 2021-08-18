<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

// custom expects
expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});


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
