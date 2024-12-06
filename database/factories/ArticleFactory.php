<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
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
            'slug' => $this->faker->unique()->slug(),
            'content' => $this->faker->paragraphs(3, true),
            'featured_image' => $this->faker->optional()->imageUrl(),
            'excerpt' => $this->faker->optional()->paragraph(),
            'status' => 'published',
            'author_id' => \App\Models\User::factory(),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
