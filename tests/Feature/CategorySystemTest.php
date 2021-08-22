<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;
use function Pest\Laravel\withExceptionHandling;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

it('should create a category using name and slug', function () {
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
    withExceptionHandling();

    $category = scaffoldNewCategory(['slug' => 'bad slug']);


    expect(post("/api/categories", $category)->status())
        ->toBeInvalid();

    assertDatabaseMissing('categories', $category);
});

test('a category should have a slug', function () {
    withExceptionHandling();
    $category = scaffoldNewCategory(['slug' => '']);

    expect(post('/api/posts', $category)->status())
        ->toBeInvalid();
});

it('should use a unique a slug for each category', function () {
    withExceptionHandling();

    $category = addNewCategory()->toArray();

    expect(post("api/categories", $category)->status())
        ->toBeInvalid();

    assertDatabaseMissing('categories', $category);
});
