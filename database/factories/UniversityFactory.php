<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UniversityFactory extends Factory
{
    protected $model = University::class;
    protected static $counter = 1;

    public function definition(): array
    {
        $universities = [
            'İstanbul Teknik',
            'Boğaziçi',
            'Orta Doğu Teknik',
            'Yıldız Teknik',
            'Hacettepe',
            'Ankara',
            'Ege',
            'Dokuz Eylül',
            'Marmara',
            'İstanbul'
        ];

        $name = $universities[static::$counter - 1] . ' Üniversitesi';
        $slug = 'university-' . static::$counter;
        static::$counter++;

        return [
            'name' => $name,
            'city' => $this->faker->city(),
            'slug' => $slug,
            'is_active' => true,
        ];
    }
}
