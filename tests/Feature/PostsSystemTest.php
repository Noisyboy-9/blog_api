<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;


beforeEach(function () {
    withoutExceptionHandling();
});

it('can create a post with title, desc, body, slug', function () {
    $category = addNewCategory();
    $post = scaffoldNewPost(['category_id' => $category->id]);

    expect(post("/api/posts", $post)->content())
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

    expect(post("/api/posts", $post)->status())
        ->toBeInvalid();

    assertDatabaseMissing('posts', $post);
});

test('every post should have a description', function () {
    withExceptionHandling();
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body',
        'description' => null
    ];

    expect(post("/api/posts", $post)->status())
        ->toBeInvalid();

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

    expect(post("api/posts", $post)->status())
        ->toBeInvalid();

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

    expect(post("/api/posts", $post2)->status())
        ->toBeInvalid();

    assertDatabaseMissing('posts', $post2);
});

it('should fetch all posts', function () {
    addNewPost();
    addNewPost();
    addNewPost();

    expect(get('/api/posts')->content())
        ->json()
        ->data
        ->toHaveCount(3);
});

it("should fetch a post by its slug", function () {
    $post = addNewPost();

    expect(get('/api/posts/' . $post->slug)->content())
        ->json()
        ->data
        ->json()
        ->toBeArray()
        ->toHaveKey('title', $post->title)
        ->toHaveKey('body', $post->body)
        ->toHaveKey('description', $post->description)
        ->toHaveKey('slug', $post->slug);
});

test('a post can be update', function () {
    $post = addNewPost();
    assertDatabaseHas('posts', [
        'title' => $post->title,
        'description' => $post->description,
        'slug' => $post->slug,
        'body' => $post->body
    ]);

    expect(patch("/api/posts/$post->slug", [
        'slug' => $post->slug,
        'title' => 'the new title'
    ])->status())->toEqual(204);

    assertDatabaseMissing('posts', $post->toArray());

    assertDatabaseHas('posts', [
        'id' => $post->id,
        'slug' => $post->slug,
        'title' => 'the new title',
        'description' => $post->description,
        'body' => $post->body
    ]);
});

it('should be able to update one or more of its fields', function () {
    $post = addNewPost();
    assertDatabaseHas('posts', [
        'title' => $post->title,
        'description' => $post->description,
        'slug' => $post->slug,
        'body' => $post->body
    ]);

    expect(patch("/api/posts/$post->slug", [
        'slug' => $post->slug,
        'description' => 'this is the new post body, this is the best description I can come up with'
    ])->status())->toEqual(204);

    assertDatabaseMissing('posts', $post->toArray());

    assertDatabaseHas('posts', [
        'id' => $post->id,
        'slug' => $post->slug,
        'title' => $post->title,
        'description' => 'this is the new post body, this is the best description I can come up with',
        'body' => $post->body
    ]);
});

it('should be able to update its slug', function () {
    $post = addNewPost();

    assertDatabaseHas('posts', [
        'title' => $post->title,
        'description' => $post->description,
        'slug' => $post->slug,
        'body' => $post->body
    ]);

    expect(patch("/api/posts/$post->slug", [
        'id' => $post->id,
        'slug' => 'the-new-slug',
    ])->status())->toEqual(204);

    assertDatabaseMissing('posts', $post->toArray());

    assertDatabaseHas('posts', [
        'id' => $post->id,
        'slug' => 'the-new-slug',
        'title' => $post->title,
        'description' => $post->description,
        'body' => $post->body
    ]);
});

it('should be able to delete a post by its slug', function () {
    $post = addNewPost();
    assertDatabaseHas('posts', [
        'title' => $post->title,
        'body' => $post->body,
        'slug' => $post->slug,
        'description' => $post->description
    ]);

    expect(delete("/api/posts/$post->slug")->status())->toEqual(204);

    assertDatabaseMissing('posts', [
        'id' => $post->id,
        'slug' => $post->slug,
        'title' => $post->title,
        'body' => $post->body,
        'description' => $post->description
    ]);
});
