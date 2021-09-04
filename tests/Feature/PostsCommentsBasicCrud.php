<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn () => withoutExceptionHandling());

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
    $owner = signIn();
    $post = addNewPost();
    $comment = addNewComment([
        'post_id' => $post->id,
        'owner_id' => $owner->id
    ]);

    assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'body' => $comment['body'],
        'owner_id' => $owner->id
    ]);

    expect(deleteJson("/api/posts/{$post->slug}/comments/{$comment->id}"))
        ->status()->toEqual(204);

    assertDatabaseMissing('comments', [
        'post_id' => $post->id,
        'body' => $comment['body'],
        'owner_id' => $owner->id
    ]);
});

it('should be logged in to delete a comment', function () {
    $post = addNewPost();
    $comment = addNewComment(['post_id' => $post->id]);
    deleteJson("/api/posts/{$post->slug}/comments/{$comment->id}");
})->throws(AuthenticationException::class);

it('should own the comment in order to delete it', function () {
    $user = signIn();
    $post = addNewPost();
    $comment = addNewComment(['post_id' => $post->id]); // a comment that $user does not own.
    deleteJson("/api/posts/{$post->slug}/comments/{$comment->id}");
})->throws(UnauthorizedException::class);

it('should be able to update a comment body', closure: function () {
    $owner = signIn();
    $post = addNewPost();
    $oldComment = addNewComment([
        'post_id' => $post->id,
        'owner_id' => $owner->id
    ]);

    assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'owner_id' => $owner->id,
        'body' => $oldComment->body
    ]);

    $newComment = [
        'id' => $oldComment->id,
        'post_id' => $post['id'],
        'body' => 'this is the new comment body'
    ];

    $response = patchJson("/api/posts/{$post->slug}/comments/{$oldComment->id}", $newComment);
    expect($response->content())
        ->json()
        ->message->toEqual("Comment has been updated successfully")
        ->data->toBeArray()
        ->toHaveKey('body', $newComment['body'])
        ->toHaveKey('post_id', $newComment['post_id'])
        ->toHaveKey('owner_id', $owner->id)
        ->and($response->status())->toEqual(200);

    assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'owner_id' => $owner->id,
        'body' => $newComment['body']
    ]);
    assertDatabaseMissing('comments', [
        'post_id' => $post->id,
        'owner_id' => $owner->id,
        'body' => $oldComment->body
    ]);
});

test('a user should own a comment to be able to update a comment', function () {
    $user = signIn();
    $post = addNewPost();
    $oldComment = addNewComment(['post_id' => $post->id]); // a comment which $user doesn't own

    $newComment = [
        'id' => $oldComment->id,
        'post_id' => $post['id'],
        'body' => 'this is the new comment body'
    ];
    patchJson("/api/posts/{$post->slug}/comments/{$oldComment->id}", $newComment);
})->throws(UnauthorizedException::class);

it('should be able to get all comments related to a post', function () {
    signIn();
    $post = addNewPost();

    $comments = [
        addNewComment(['post_id' => $post->id]),
        addNewComment(['post_id' => $post->id]),
        addNewComment(['post_id' => $post->id]),
    ];


    $response = getJson("/api/posts/{$post->slug}/comments");

    expect($response->content())
        ->json()
        ->toHaveCount(count($comments))
        ->and($response->status())->toEqual(200);
});

it('should be authenticated in order to see comments related to a post', function () {
    $post = addNewPost();

    $comments = [
        addNewComment(['post_id' => $post->id]),
        addNewComment(['post_id' => $post->id]),
        addNewComment(['post_id' => $post->id]),
    ];


    getJson("/api/posts/{$post->slug}/comments");
})->throws(AuthenticationException::class);
