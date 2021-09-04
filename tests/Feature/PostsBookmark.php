<?php

use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn () => withoutExceptionHandling());

it('can bookmark a post', function () {
    $user = signIn();
    $post = addNewPost();

    $response = getJson("api/posts/$post->slug/bookmark");

    expect($response->content())
        ->json()
        ->message->toEqual("Post has been bookmarked successfully")
        ->data
        ->toBeArray()
        ->toHaveKey('title', $post->title)
        ->toHaveKey('slug', $post->slug)
        ->toHaveKey('body', $post->body)
        ->and($response->status())->toEqual(201);

    assertDatabaseHas('bookmarks', [
        'user_id' => $user->id,
        'post_id' => $post->id
    ]);
});

it('should be logged in to be able to bookmark a post', function () {
    $post = addNewPost();
    expect(getJson("/api/posts/$post->slug/bookmark"));
})->throws(AuthenticationException::class);

it('should be able to get a users bookmarks', function () {
    $user = signIn();
    $posts = [
        addNewPost(),
        addNewPost(),
        addNewPost(),
        addNewPost(),
    ];

    foreach ($posts as $post) {
        $user->bookmark($post);
    }

    $response = getJson('/api/user/bookmarks');

    expect($response->content())
        ->json()
        ->toBeArray()
        ->toHaveCount(count($posts))
        ->and($response->status())->toEqual(200);
});

it('a user should be logged in to get a users bookmarks')
    ->getJson('/api/user/bookmarks')
    ->throws(AuthenticationException::class);
