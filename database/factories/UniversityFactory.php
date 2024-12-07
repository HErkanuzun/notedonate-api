<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\University>
 */
class UniversityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' University',
            'city' => $this->faker->city(),
            'country' => 'Turkey',
            'website' => $this->faker->url(),
            'description' => $this->faker->paragraph(),
            'logo' => $this->faker->imageUrl(200, 200, 'university')
        ];
    }
}