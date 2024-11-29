<?php

namespace Database\Factories;

use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleCategoryFactory extends Factory
{
    protected $model = ArticleCategory::class;
    protected static $counter = 1;

    public function definition(): array
    {
        $categories = [
            'Teknoloji',
            'Bilim',
            'Eğitim',
            'Sağlık',
            'Spor',
            'Sanat',
            'Müzik',
            'Edebiyat',
            'Tarih',
            'Felsefe'
        ];

        $name = $categories[static::$counter % count($categories)];
        $slug = 'category-' . static::$counter;
        static::$counter++;

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->faker->sentence()
        ];
    }
}
