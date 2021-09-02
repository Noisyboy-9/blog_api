<?php

use App\blog_api\posts\PostStatusEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

it('should belong to a category', function () {
    $category = addNewCategory();
    $post = addNewPost(['category_id' => $category->id]);

    expect($post->category)
        ->not->toBeNull()
        ->toBeInstanceOf(Category::class)
        ->and($post->category->is($category))
        ->toBeTrue();
});

it('should belong to a owner', function () {
    $owner = signIn();
    $post = addNewPost(['owner_id' => $owner->id]);

    expect($post->owner)
        ->not->toBeNull()
        ->and($post->owner->is($owner))->toBeTrue();
});

it('may have multiple comments', function () {
    $post = addNewPost();
    $comment1 = addNewComment(['post_id' => $post->id]);
    $comment2 = addNewComment(['post_id' => $post->id]);
    $comment3 = addNewComment(['post_id' => $post->id]);

    expect($post->comments)
        ->not->toBeNull()
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3)
        ->and($post->comments->contains($comment1))->toBeTrue()
        ->and($post->comments->contains($comment2))->toBeTrue()
        ->and($post->comments->contains($comment3))->toBeTrue();
});

it('should know if it is in published mode', function () {
    $post = addNewPost(['status' => PostStatusEnum::PUBLISHED]);
    expect($post->isPublished())->toBeTrue();
});

it('should know if it is in draft mode', function () {
    $post = addNewPost(['status' => PostStatusEnum::DRAFT]);
    expect($post->isDrafted())->toBeTrue();
});

it('can publish itself', function () {
    $post = addNewPost();

    expect($post->isPublished())->toBeFalse();
    expect($post->isDrafted())->toBeTrue();

    $post->publish();

    expect($post->isPublished())->toBeTrue();
    expect($post->isDrafted())->toBeFalse();
});
