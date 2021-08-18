<?php

use function Pest\Laravel\assertDatabaseHas;

it('can create a post', function () {
    $post = [
        'title' => 'this is the post title',
        'body' => 'this is the post body what do you think?'
    ];

    POST("/posts", $post)->assertStatus(201);

   assertDatabaseHas('posts', $post);
});
