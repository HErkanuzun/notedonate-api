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
        $commentableTypes = [
            \App\Models\Article::class,
            \App\Models\Note::class,
        ];
        
        $commentableType = $this->faker->randomElement($commentableTypes);
        
        return [
            'content' => $this->faker->paragraph(),
            'user_id' => \App\Models\User::factory(),
            'commentable_type' => $commentableType,
            'commentable_id' => $commentableType::factory(),
            'is_approved' => $this->faker->boolean(90),
        ];
    }
}
