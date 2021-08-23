<?php


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;
use function Pest\Laravel\withoutExceptionHandling;

beforeEach(fn() => withoutExceptionHandling());

test('every post should have a category', function () {
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

it('should be able to retrieve all posts related to category using query string', function () {
    signIn();
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

it('should not return a post not related to the category in the query string', function () {
    signIn();
    $category = addNewCategory();
    $category->posts()->create(scaffoldNewPost());
    $category->posts()->create(scaffoldNewPost());
    addNewPost(); // post with category id not equal to $category->id

    $response = get($category->path());

    expect($response->status())
        ->toEqual(200)
        ->and($response->content())
        ->json()
        ->data
        ->toHaveCount(2);
});
