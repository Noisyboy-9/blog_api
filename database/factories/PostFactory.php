<?php

namespace Database\Factories;

use App\blog_api\posts\PostStatusEnum;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['title' => "string", 'body' => "string", 'description' => "string", 'slug' => "mixed", 'category_id' => "\Illuminate\Database\Eloquent\Factories\Factory", 'owner_id' => "\Illuminate\Database\Eloquent\Factories\Factory", 'status' => "int"])]
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(5),
            'body' => $this->faker->sentence(100),
            'description' => $this->faker->sentence(50),
            'slug' => $this->faker->unique()->slug(),
            'category_id' => Category::factory(),
            'owner_id' => User::factory(),
            'status' => PostStatusEnum::DRAFT
        ];
    }
}
