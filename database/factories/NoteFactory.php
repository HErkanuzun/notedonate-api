<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(2, true),
            'user_id' => \App\Models\User::factory(),
            'file_path' => $this->faker->optional()->filePath(),
            'category' => $this->faker->randomElement(['Study', 'Research', 'Personal', 'Project']),
            'status' => $this->faker->randomElement(['public', 'private']),
        ];
    }
}
