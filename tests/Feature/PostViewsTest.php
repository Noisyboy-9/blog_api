<?php

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn () => withoutExceptionHandling());

it('should store the view of a post for every user', function () {
    $viewer = signIn();
    $post = addNewPost();

    expect(getJson("/api/posts/{$post->slug}")->status())->toEqual(200);

    assertDatabaseHas('views', [
        'viewer_id' => $viewer->id,
        'post_id' => $post->id
    ]);
});

it('should not store view for duplicate users', function () {
    $viewer = signIn();
    $post = addNewPost();

    expect(getJson("/api/posts/{$post->slug}")->status())->toEqual(200);

    assertDatabaseHas('views', [
        'viewer_id' => $viewer->id,
        'post_id' => $post->id
    ]);

    assertDatabaseCount('views', 1);  //asserting that no duplicate views are stored.
});

it('should give the view_count when fetching a single post', function () {
    signIn();
    $post = addNewPost();

    expect(getJson("/api/posts/{$post->slug}")->content())
    ->json()
    ->data->json()
    ->toBeArray()
    ->toHaveKey('view_count', $post->viewsCount());
});