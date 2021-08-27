<?php

use App\blog_api\posts\PostStatusEnum;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should have status for every post', function () {
    $owner = signIn();
    $post = scaffoldNewPost([
        'owner_id' => $owner->id,
        'status' => PostStatusEnum::PUBLISHED
    ]);
    $response = postJson('/api/posts', $post);

    expect($response->content())
        ->json()
        ->data
        ->toHaveKey('status', PostStatusEnum::PUBLISHED);

    assertDatabaseHas('posts', [
        'title' => $post['title'],
        'slug' => $post['slug'],
        'body' => $post['body'],
        'description' => $post['description'],
        'status' => PostStatusEnum::PUBLISHED
    ]);
});

test('post status cant have any other value than published or draft', function () {
    $owner = signIn();
    $post = scaffoldNewPost([
        'owner_id' => $owner->id,
        'status' => 'bad value'
    ]);
    postJson('/api/posts', $post);
})->throws(ValidationException::class);

test('all posts have default status of draft', function () {
    $owner = signIn();
    $post = scaffoldNewPost(['owner_id' => $owner->id]); // a post with no status defined

    $response = postJson('/api/posts', $post);

    expect($response->content())
        ->json()
        ->data
        ->toHaveKey('status', PostStatusEnum::DRAFT);

    assertDatabaseHas('posts', [
        'title' => $post['title'],
        'slug' => $post['slug'],
        'body' => $post['body'],
        'description' => $post['description'],
        'status' => PostStatusEnum::DRAFT
    ]);
});

test('a post can be published', function () {
    $owner = signIn();
    $post = addNewPost(['owner_id' => $owner->id]);

    expect($post->published())->toBeFalse();
    expect($post->drafted())->toBeTrue();

    $response = postJson("/api/posts/{$post->slug}/publish");

    expect($response->status())
        ->toEqual(200)
        ->and($response->content())
        ->json()
        ->message->toEqual("Post published successfully")
        ->data
        ->toBeArray()
        ->toHaveKey('status', PostStatusEnum::PUBLISHED);

    expect($post->refresh()->published())->toBeTrue();
    expect($post->refresh()->drafted())->toBeFalse();
});

test('user should own the post in order to publish it', function () {
    $user = signIn();
    $post = addNewPost(); // a drafted post that isn't owned by $user

    postJson("/api/posts/{$post->slug}/publish");
})->throws(UnauthorizedException::class);

it('should not send any draft posts when fetching all posts', function () {
    signIn();
    $draftedPosts = [
        addNewPost(),
        addNewPost(),
        addNewPost(),
        addNewPost(),
    ];

    expect(getJson("/api/posts")->content())
        ->json()
        ->data
        ->toHaveCount(0);
});

