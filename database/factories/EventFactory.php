<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = Carbon::instance($startDate)->addHours($this->faker->numberBetween(1, 48));

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $this->faker->optional()->address,
            'type' => $this->faker->randomElement(['general', 'meeting', 'deadline', 'workshop']),
            'status' => $this->faker->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
            'created_by' => User::factory()
        ];
    }

    /**
     * Upcoming event state.
     */
    public function upcoming()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'upcoming',
                'start_date' => $this->faker->dateTimeBetween('now', '+1 month')
            ];
        });
    }

    /**
     * Completed event state.
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'start_date' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
                'end_date' => $this->faker->dateTimeBetween('-1 day', 'now')
            ];
        });
    }
}
