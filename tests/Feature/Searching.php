<?php

use App\blog_api\Posts\PostStatusEnum;
use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn () => withoutExceptionHandling());

it('should retrieve posts by searching for slug', function () {
    signIn();
    $post = addNewPost(['status' => PostStatusEnum::PUBLISHED]);

    $response = get("/api/feed?search=$post->slug");

    expect($response->content())
        ->json()
        ->data
        ->toHaveCount(1)
        ->and($response->status())
        ->toEqual(200);
});

it('should retrieve posts by searching for title', function () {
    signIn();
    $post = addNewPost(['status' => PostStatusEnum::PUBLISHED]);

    $response = get("/api/feed?search=$post->title");

    expect($response->content())
        ->json()
        ->data
        ->toHaveCount(1)
        ->and($response->status())
        ->toEqual(200);
});

it('should retrieve posts by searching for body', function () {
    signIn();
    $post = addNewPost(['status' => PostStatusEnum::PUBLISHED]);

    $response = get("/api/feed?search=$post->body");

    expect($response->content())
        ->json()
        ->data
        ->toHaveCount(1)
        ->and($response->status())
        ->toEqual(200);
});

it('should retrieve posts by searching for description', function () {
    signIn();
    $post = addNewPost(['status' => PostStatusEnum::PUBLISHED]);

    $response = get("/api/feed?search=$post->description");

    expect($response->content())
        ->json()
        ->data
        ->toHaveCount(1)
        ->and($response->status())
        ->toEqual(200);
});
