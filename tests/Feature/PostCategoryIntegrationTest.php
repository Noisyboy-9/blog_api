<?php


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use function Pest\Laravel\assertDatabaseHas;

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
