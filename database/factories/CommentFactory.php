<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => $this->faker->numberBetween(1, 10),
            "post_id" => $this->faker->numberBetween(1, 10),
            // parent_id should be null at first, if not the seeding will fail
            // because there are no comments yet at first for having a parent_id
            "parent_id" => $this->faker->numberBetween(4, 10),
            "content" => $this->faker->sentence(),
        ];
    }
}
