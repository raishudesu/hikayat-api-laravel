<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reportable = $this->faker->randomElement([
            \App\Models\Post::class,
            \App\Models\Comment::class,
            \App\Models\User::class,
        ]);

        return [
            'reporter_id' => User::factory(),
            'reportable_id' => $reportable::factory(),
            'reportable_type' => $reportable,
            'type' => $this->faker->randomElement(['spam', 'inappropriate', 'misinformation']),
            'status' => $this->faker->randomElement(['pending', 'reviewed', 'dismissed']),
        ];
    }
}
