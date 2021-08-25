<?php

use Illuminate\Auth\AuthenticationException;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should create a category using name and slug', function () {
    signIn();
    $category = scaffoldNewCategory();

    expect(post("/api/categories", $category)->content())
        ->json()
        ->message->toEqual("Category has been created successfully")
        ->data->toBeArray()
        ->toHaveKey("name", $category['name'])
        ->toHaveKey("slug", $category['slug']);

    assertDatabaseHas('categories', $category);
});

test('a category should have a slug-like string', function () {
    signIn();
    withExceptionHandling();

    $category = scaffoldNewCategory(['slug' => 'bad slug']);


    expect(post("/api/categories", $category)->status())
        ->toBeInvalid();

    assertDatabaseMissing('categories', $category);
});

test('a category should have a slug', function () {
    signIn();
    withExceptionHandling();
    $category = scaffoldNewCategory(['slug' => '']);

    expect(post('/api/posts', $category)->status())
        ->toBeInvalid();
});

it('should use a unique a slug for each category', function () {
    signIn();
    withExceptionHandling();

    $category = addNewCategory()->toArray();

    expect(post("api/categories", $category)->status())
        ->toBeInvalid();

    assertDatabaseMissing('categories', $category);
});

it('be able to delete a category', closure: function () {
    signIn();
    $category = addNewCategory();

    assertDatabaseHas('categories', [
        'name' => $category->name,
        'slug' => $category->slug
    ]);

    expect(deleteJson("/api/categories/$category->slug")->status())
        ->toEqual(204);

    assertDatabaseMissing('categories', [
        'name' => $category->name,
        'slug' => $category->slug
    ]);
});

it('should be logged in to be able to delete a category', function () {
    $category = scaffoldNewCategory();
    deleteJson("/api/categories/" . $category['slug']);
})->throws(AuthenticationException::class);

it('should be logged in to be able to create a category', function () {
    $category = scaffoldNewCategory();
    postJson("/api/categories", $category);
})->throws(AuthenticationException::class);
