<?php

namespace Database\Factories;
use Illuminate\Support\Carbon;
use App\Models\Exam;
use App\Models\User;

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
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 7) . ' days');

        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'total_marks' => $this->faker->numberBetween(50, 100),
            'duration' => $this->faker->randomElement([30, 45, 60, 90, 120]),
            'created_by' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['active', 'completed', 'scheduled']),
        ];
    }
}
