<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should be able to create a comment on a post', function () {
    signIn();
    $post = addNewPost();
    $comment = scaffoldNewComment(['post_id' => $post->id]);

    $response = postJson("/api/posts/$post->slug/comments", $comment);

    expect($response->content())
        ->json()
        ->message->toEqual("Commented successfully")
        ->data
        ->toHaveKey('body', $comment['body'])
        ->toHaveKey('post_id', $comment['post_id']);

    assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'body' => $comment['body']
    ]);
});

it('should have belong to a post', function () {
    signIn();
    $comment = scaffoldNewComment(['post_id' => '']);
    postJson('/api/posts', $comment);
})->throws(ValidationException::class);

it('should have a body', function () {
    signIn();
    $comment = scaffoldNewComment(['body' => '']);
    postJson('/api/posts', $comment);
})->throws(ValidationException::class);

it('should be logged in to create a comment', function () {
    $post = addNewPost();
    $comment = scaffoldNewComment(['post_id' => $post->id]);
    postJson("/api/posts/$post->slug/comments", $comment);
})->throws(AuthenticationException::class);

it('should store a owner with itself', function () {
    $owner = signIn();
    $post = addNewPost();
    $comment = scaffoldNewComment(['post_id' => $post->id]);

    expect(postJson("/api/posts/{$post->slug}/comments", $comment))
        ->status()->toEqual(201);

    assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'body' => $comment['body'],
        'owner_id' => $owner->id
    ]);
});

it('should be able to delete a comment', function () {

})->skip();


it('should be logged in to delete a comment', function () {

})->skip();

it('should own the comment in order to delete it', function () {

})->skip();

it('should be able to update a comment body', function () {

})->skip();
