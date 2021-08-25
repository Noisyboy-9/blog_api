<?php

use App\Models\Post;
use App\Models\User;

it('should relate to a post', function () {
    $post = addNewPost();
    $comment = addNewComment(['post_id' => $post->id]);

    expect($comment->post)
        ->not->toBeNull()
        ->toBeInstanceOf(Post::class)
        ->and($comment->post->is($post))->toBeTrue();
});

it('should have be able to get its owner', function () {
    $owner = signIn();
    $comment = addNewComment(['owner_id' => $owner->id]);

    expect($comment->owner)
        ->not->toBeNull()
        ->toBeInstanceOf(User::class)
        ->and($comment->owner->is($owner))->toBeTrue();
});
