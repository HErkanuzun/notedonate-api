<?php

namespace Database\Factories;
use Illuminate\Support\Carbon;
use App\Models\Exam;
use App\Models\User;
use App\Models\University;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Exam::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'total_marks' => $this->faker->numberBetween(50, 100),
            'duration' => $this->faker->numberBetween(30, 180),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['active', 'completed', 'scheduled']),
            'subject' => $this->faker->randomElement(['Mathematics', 'Physics', 'Chemistry', 'Biology', 'Computer Science']),
            'cover_image' => $this->faker->optional()->imageUrl() ?: 'https://images.unsplash.com/photo-1623074716850-ba4c90d49f2f?q=80&w=2598&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
        ];
    }
}
