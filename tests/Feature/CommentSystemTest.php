<?php

use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should be able to create a comment on a post', function () {
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
