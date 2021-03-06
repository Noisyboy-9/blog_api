<?php

beforeEach(fn () => \Illuminate\Support\Facades\Redis::flushAll());

use Illuminate\Support\Collection;

test('a user may have a post', function () {
    $user = signIn();
    $post = addNewPost(['owner_id' => $user]);

    expect($user->posts->contains($post))->toBeTrue();
});

it('should be able to have multiple posts ', function () {
    $user = signIn();
    addNewPost(['owner_id' => $user]);
    addNewPost(['owner_id' => $user]);
    addNewPost(['owner_id' => $user]);
    addNewPost(['owner_id' => $user]);
    addNewPost(['owner_id' => $user]);

    expect($user->posts)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(5);
});

it('may have many comments', function () {
    $user = signIn();
    $post = addNewPost();
    $comment1 = addNewComment([
        'post_id' => $post->id,
        'owner_id' => $user->id
    ]);
    $comment2 = addNewComment([
        'post_id' => $post->id,
        'owner_id' => $user->id
    ]);
    $comment3 = addNewComment([
        'post_id' => $post->id,
        'owner_id' => $user->id
    ]);


    expect($user->comments)
        ->not->toBeNull()
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3)
        ->and($user->comments->contains($comment1))->toBeTrue()
        ->and($user->comments->contains($comment2))->toBeTrue()
        ->and($user->comments->contains($comment3))->toBeTrue();
});

it('can bookmark a post', function () {
    $user = signIn();
    $post = addNewPost();

    $user->bookmark($post);

    expect($user->hasBookmark($post))->toBeTrue();
});

it('may have many bookmarks', function () {
    $user = signIn();
    $post = addNewPost();

    $user->bookmark($post);


    expect($user->bookmarks())
        ->toBeInstanceOf(Collection::class)
        ->toHaveLength(1);
});

it('can check if it has already bookmarked a post', function () {
    $user = signIn();
    $post = addNewPost();

    $user->bookmark($post);

    expect($user->hasBookmark($post))->toBeTrue();
});
