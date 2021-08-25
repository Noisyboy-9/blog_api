<?php

use Illuminate\Database\Eloquent\Collection;

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
