<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    protected static $counter = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        $slug = 'article-' . static::$counter;
        static::$counter++;

        return [
            'title' => $title,
            'slug' => $slug,
            'content' => $this->faker->paragraphs(3, true),
            'excerpt' => $this->faker->paragraph(),
            'featured_image' => null, // Geçici olarak devre dışı bırakıldı
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'author_id' => User::factory(),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'view_count' => $this->faker->numberBetween(0, 1000),
            'like_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Published article state.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Draft article state.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
