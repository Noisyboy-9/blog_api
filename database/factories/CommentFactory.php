<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    #[ArrayShape(['owner_id' => "\Illuminate\Database\Eloquent\Factories\Factory", 'post_id' => "\Illuminate\Database\Eloquent\Factories\Factory", 'body' => "string"])]
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'post_id' => Post::factory(),
            'body' => $this->faker->sentence()
        ];
    }
}
