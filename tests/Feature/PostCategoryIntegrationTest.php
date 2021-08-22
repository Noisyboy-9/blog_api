<?php


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

test('every Post should have a Category', function () {
    $category = addNewCategory();
    $post = addNewPost(['category_id' => $category->id]);

    assertDatabaseHas('posts', [
        'title' => $post->title,
        'slug' => $post->slug,
        'category_id' => $category->id
    ]);

    expect($category->posts)
        ->not->toBeNull()
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1)
        ->and($category->posts->contains($post))
        ->toBeTrue();


    expect($post->category)
        ->not->toBeNull()
        ->toBeInstanceOf(Category::class)
        ->and($post->category()->is($category))
        ->toBeTrue();
});

it('should be able to retrieve all posts related to Category using query string', function () {
    $category = addNewCategory();
    $category->posts()->create(scaffoldNewPost());
    $category->posts()->create(scaffoldNewPost());

    $response = get($category->path());

    expect($response->status())
        ->toEqual(200)
        ->and($response->content())
        ->json()
        ->data
        ->toHaveCount(2);
});

it('should not return a Post not related to the Category in the query string', function () {
    $category = addNewCategory();
    $category->posts()->create(scaffoldNewPost());
    $category->posts()->create(scaffoldNewPost());
    addNewPost(); // Post with Category id not equal to $Category->id

    $response = get($category->path());

    expect($response->status())
        ->toEqual(200)
        ->and($response->content())
        ->json()
        ->data
        ->toHaveCount(2);
});
