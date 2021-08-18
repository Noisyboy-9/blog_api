<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(function () {
    withoutExceptionHandling();
});

it('can create a post', function () {
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body what do you think?'
    ];

    POST("/api/posts", $post)->assertStatus(201);

    assertDatabaseHas('posts', $post);
});

it('should not create a post with invalid data', function () {
    withExceptionHandling();
    $post = [
        'title' => null,
        'body' => 'the post body'
    ];
    POST("/api/posts", $post)->assertStatus(302);
    assertDatabaseMissing('posts', $post);
});
