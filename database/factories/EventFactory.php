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
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 48) . ' hours');

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(2, true),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'created_by' => \App\Models\User::factory(),
            'location' => $this->faker->optional()->address(),
            'cover_image' => $this->faker->optional()->imageUrl() ?: 'https://images.unsplash.com/photo-1623074716850-ba4c90d49f2f?q=80&w=2598&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            'type' => $this->faker->randomElement(['general', 'meeting', 'deadline']),
            'status' => $this->faker->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
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
