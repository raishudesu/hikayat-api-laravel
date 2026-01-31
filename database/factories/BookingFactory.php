<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hiker_id' => User::factory(),
            'guide_id' => User::factory()->state(['is_verified_guide' => true]),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'declined', 'completed']),
            'hike_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'notes' => $this->faker->paragraph(),
        ];
    }
}
