<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    withoutExceptionHandling();
});

it('can create a post with title, desc, body, slug', function () {
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body what do you think?',
        'description' => 'this is the post description',
        'slug' => 'this-is-a-slug'
    ];

    POST("/api/posts", $post)->assertstatus(201);

    assertDatabaseHas('posts', $post);
});

it('should not create a post with invalid data', function () {
    withExceptionHandling();
    $post = [
        'title' => null,
        'body' => 'the post body',
        'description' => 'this is the desc'
    ];
    POST("/api/posts", $post)->assertStatus(302);
    assertDatabaseMissing('posts', $post);
});

test('every post should have a description', function () {
    withExceptionHandling();
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body',
        'description' => null
    ];

    POST("/api/posts", $post)->assertStatus(302);

    assertDatabaseMissing('posts', $post);
});

test('every post should have a slug', function () {
    withExceptionHandling();
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body',
        'description' => 'this is the post description',
        'slug' => null
    ];

    POST("/api/posts", $post)->assertStatus(302);

    assertDatabaseMissing('posts', $post);
});

