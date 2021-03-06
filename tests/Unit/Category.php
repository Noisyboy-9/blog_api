<?php

use Illuminate\Database\Eloquent\Collection;

it('should have a list of posts', function () {
    $category = addNewCategory();
    $post = addNewPost(['category_id' => $category->id]);

    expect($category->posts)
        ->not->toBeNull()
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($category->posts->contains($post))
        ->toBeTrue();
});

it('should know its path', function () {
    $category = addNewCategory();
    expect($category->path())->toEqual("/api/feed?category=$category->slug");
});
