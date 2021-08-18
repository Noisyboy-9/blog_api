<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;
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

    expect(post("/api/posts", $post)->baseResponse->content())
        ->json()
        ->message->toEqual("Post created successfully!")
        ->data->toBeArray();

    assertDatabaseHas('posts', $post);
});

it('should not create a post with invalid data', function () {
    withExceptionHandling();
    $post = [
        'title' => null,
        'body' => 'the post body',
        'description' => 'this is the desc'
    ];

    expect(post("/api/posts", $post)->status())->toEqual(302);

    assertDatabaseMissing('posts', $post);
});

test('every post should have a description', function () {
    withExceptionHandling();
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body',
        'description' => null
    ];

    expect(post("/api/posts", $post)->status())->toEqual(302);

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

    expect(post("api/posts", $post)->status())->toEqual(302);

    assertDatabaseMissing('posts', $post);
});

test('slugs should be unique', function () {
    withExceptionHandling();

    $post1 = addNewPost(['slug' => 'the-same-slug']);

    assertDatabaseHas('posts', [
        'title' => $post1->title,
        'body' => $post1->body,
        'description' => $post1->description,
        'slug' => $post1->slug
    ]);

    $post2 = scaffoldNewPost(['slug' => 'the-same-slug']);

    expect(post("/api/posts", $post2)->status())->toEqual(302);

    assertDatabaseMissing('posts', $post2);
});
