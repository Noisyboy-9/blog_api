<?php

use App\Models\Post;

it('should relate to a post', function () {
    $post = addNewPost();
    $comment = addNewComment(['post_id' => $post->id]);

    expect($comment->post)
        ->not->toBeNull()
        ->toBeInstanceOf(Post::class)
        ->and($comment->post->is($post))->toBeTrue();
});
