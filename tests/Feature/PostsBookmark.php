<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Redis;
use Pest\Expectation;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn () => withoutExceptionHandling() && Redis::flushAll());

it('can bookmark a post', function () {
    $user = signIn();
    $post = addNewPost();

    $response = postJson("api/posts/$post->slug/bookmark");

    expect($response->content())
        ->json()
        ->message->toEqual("Post has been bookmarked successfully")
        ->data
        ->toBeArray()
        ->toHaveKey('title', $post->title)
        ->toHaveKey('slug', $post->slug)
        ->toHaveKey('body', $post->body)
        ->and($response->status())->toEqual(201);

    expect($post->is(unserialize(Redis::sMembers("users:$user->id:bookmarks")[0])))->toBeTrue();
});

it('should be logged in to be able to bookmark a post', function () {
    $post = addNewPost();
    expect(postJson("/api/posts/$post->slug/bookmark"));
})->throws(AuthenticationException::class);

it('should be able to get a users bookmarks', function () {
    $user = signIn();
    $posts = [
        addNewPost(),
        addNewPost(),
        addNewPost(),
        addNewPost(),
    ];

    foreach ($posts as $post) {
        $user->bookmark($post);
    }

    $response = getJson('/api/user/bookmarks');

    expect($response->content())
        ->json()
        ->toBeArray()
        ->toHaveCount(count($posts))
        ->and($response->status())->toEqual(200);
});

it('a user should be logged in to get a users bookmarks')
    ->getJson('/api/user/bookmarks')
    ->throws(AuthenticationException::class);

test("a user can't bookmark a post twice", function () {
    $user = signIn();

    $post1 = addNewPost();

    $user->bookmark($post1);

    $response = postJson("api/posts/$post1->slug/bookmark");

    expect($response->content())
        ->json()
        ->message->toEqual('Post has been already bookmarked')
        ->and($response->status())->toEqual(403)
        ->and($user->bookmarks()->count())->toEqual(1);
});

it('should store the bookmakers in redis', function () {
    $user = signIn();
    $post = addNewPost();

    $response = postJson("/api/posts/$post->slug/bookmark");

    expect($response->status())->toEqual(201);
    expect(boolval(Redis::exists("users:$user->id:bookmarks")))->toBeTrue()
        ->and(Redis::scard("users:$user->id:bookmarks"))->toEqual(1);
});

it('should store the bookmakers as json in redis', function () {
    $user = signIn();
    $post = addNewPost();

    $response = postJson("/api/posts/$post->slug/bookmark");

    expect($response->status())->toEqual(201);
    expect(boolval(Redis::exists("users:$user->id:bookmarks")))->toBeTrue()
        ->and(Redis::scard("users:$user->id:bookmarks"))->toEqual(1)
        ->and(Redis::smembers("users:$user->id:bookmarks"))
        ->each(function (Expectation $expectation) use ($post) {
            $cachedPost = unserialize($expectation->value);
            return $expectation->and($post->is($cachedPost))->toBeTrue();
        });
});

it('should get the bookmarks from redis', function () {
    $user = signIn();
    $post = addNewPost();


//    cache post in bookmarks list of redis
    $user->bookmark($post);

//    get post from redis
    $cachedPost = unserialize(Redis::smembers("users:$user->id:bookmarks")[0]);

//    get bookmarks list
    $response = getJson("api/user/bookmarks");

    expect($response->status())->toEqual(200)
        ->and($response->content())
        ->json()
        ->each->toBeArray()
        ->toHaveKey('title', $cachedPost->title)
        ->toHaveKey('body', $cachedPost->body)
        ->toHaveKey('slug', $cachedPost->slug);
});
