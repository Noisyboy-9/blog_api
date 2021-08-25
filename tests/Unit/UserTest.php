<?php

test('a user may have multiple posts', function () {
    $user = signIn();
    $post = addNewPost(['owner_id' => $user]);

    expect($user->posts->contains($post))->toBeTrue();
});
