<?php

use App\blog_api\Posts\PostStatusEnum;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;

beforeEach(fn () => Redis::flushAll());

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

it('can add a viewer to the list of its viewers', function () {
    $viewer = signIn();
    $post = addNewPost();

    $post->addViewer($viewer);

    expect($post->viewsCount())
        ->toEqual(1)
        ->and($post->hasViewer($viewer))->toBeTrue();
});

it('may have many views', function () {
    $viewer = signIn();
    $post = addNewPost();

    $post->addViewer($viewer);

    expect($post->views)
        ->not()->toBeNull()
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1);
});

it('can check if a user has already viewed it or not', function () {
    $viewer = signin();
    $post = addnewpost();

    expect($post->hasViewer($viewer))
        ->toBeFalse()
        ->and($post->viewsCount())->toEqual(0);
});

it('increments view count cache when adding a new viewer', function () {
    $viewer = signin();
    $post = addnewpost();

//    before a post be viewed in redis it must have view value of zero
    expect(Redis::get("posts:$post->id:views"))->toEqual(0)
        ->and($post->viewsCount())->toEqual(0);

    $post->addViewer($viewer);

//    after post has been viewed the redis must be updated
    expect(Redis::get("posts:$post->id:views"))->toEqual(1)
        ->and($post->viewsCount())->toEqual(1);
});

it('can count its own view count', function () {
    $viewer = signIn();
    $post = addNewPost();

    $post->addViewer($viewer);
    expect($post->viewsCount())->toEqual(1);
});

it('can know if a post has been viewed by a viewer in the past or not', function () {
    $viewer = signIn();
    $post = addNewPost();

    $post->addViewer($viewer);

    expect($post->hasViewer($viewer))->toBeTrue();
});
