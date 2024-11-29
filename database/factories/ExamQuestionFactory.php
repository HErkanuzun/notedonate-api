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

    public function definition()
    {
        return [
            'exam_id' => Exam::factory(), // Sınav ile ilişkilendir
            'question' => $this->faker->sentence, // Soru metni
            'question_type' => $this->faker->randomElement(['multiple_choice', 'open_ended', 'true_or_false', 'fill_in_the_blank']), // Soru tipi
            'options' => $this->faker->randomElement([['A' => 'Option 1', 'B' => 'Option 2', 'C' => 'Option 3', 'D' => 'Option 4'], null]), // Seçenekler
            'correct_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']), // Doğru cevap
            'marks' => $this->faker->numberBetween(1, 10), // Puan
        ];
    }
}
