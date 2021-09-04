<?php

use App\Models\Post;
use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn () => withoutExceptionHandling());

it('should must be logged in to create a new user', function () {
    $post = scaffoldNewPost();
    expect(postJson("/api/posts", $post));
})->throws(AuthenticationException::class);

test('a post must have a owner', function () {
    $owner = signIn();
    $post = scaffoldNewPost();

    $response = postJson('/api/posts', $post);

    expect($response->content())
        ->json()
        ->message->toEqual('Post created successfully!')
        ->data
        ->toHaveKey('title', $post['title'])
        ->toHaveKey('owner_id', $owner->id)
        ->toHaveKey('slug', $post['slug'])
        ->and($response->status())->toEqual(201);

    assertDatabaseHas('posts', [
        'slug' => $post['slug'],
        'title' => $post['title'],
        'body' => $post['body'],
        'owner_id' => $owner->id
    ]);
});

test('a post should have a owner', function () {
    $owner = signIn();
    $post = scaffoldNewPost(['owner_id' => $owner]);

    expect(postJson("/api/posts", $post))->status()->toEqual(201);

    $postModel = Post::first();
    expect($postModel->owner->is($owner))->toBeTrue();
});
