<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExamQuestion;
use App\Models\Exam;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamQuestion>
 */
class ExamQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ExamQuestion::class;

    public function definition(): array
    {
        $options = [
            $this->faker->unique()->sentence(),
            $this->faker->unique()->sentence(),
            $this->faker->unique()->sentence(),
            $this->faker->unique()->sentence(),
        ];
        
        return [
            'exam_id' => \App\Models\Exam::factory(),
            'question' => $this->faker->paragraph(),
            'correct_answer' => $options[0],
            'options' => json_encode($options),
            'points' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'question_type' => $this->faker->randomElement(['multiple_choice', 'true_false', 'short_answer']),
        ];
    }
}
