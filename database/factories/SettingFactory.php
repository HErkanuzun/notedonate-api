<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $groups = ['general', 'theme', 'locale', 'seo', 'social', 'mail', 'system'];
        $types = ['string', 'integer', 'boolean', 'json'];

        return [
            'key' => $this->faker->unique()->word . '.' . $this->faker->word,
            'value' => $this->faker->text(50),
            'group' => $this->faker->randomElement($groups),
            'type' => $this->faker->randomElement($types),
            'description' => $this->faker->sentence(),
            'is_public' => $this->faker->boolean(80) // 80% şansla public
        ];
    }
}
