<?php

use App\Models\Category;

it('should belong to a Post', function () {
    $category = addNewCategory();
    $post = addNewPost(['category_id' => $category->id]);

    expect($post->category)
        ->not->toBeNull()
        ->toBeInstanceOf(Category::class)
        ->and($post->category->is($category))
        ->toBeTrue();
});
