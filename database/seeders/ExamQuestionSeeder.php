<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExamQuestion;

class ExamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamQuestion::create([
            'exam_id' => 1,
            'question' => 'What is the capital of France?',
            'question_type' => 'multiple_choice',
            'options' => [
                'A' => 'Paris',
                'B' => 'London',
                'C' => 'Berlin',
                'D' => 'Madrid'
            ],
            'correct_answer' => 'A',
            'marks' => 10
        ]);

        ExamQuestion::create([
            'exam_id' => 1,
            'question' => 'Is PHP a programming language?',
            'question_type' => 'true_false',
            'options' => [
                'A' => 'True',
                'B' => 'False'
            ],
            'correct_answer' => 'A',
            'marks' => 5
        ]);

        ExamQuestion::create([
            'exam_id' => 1,
            'question' => 'Explain the MVC architecture.',
            'question_type' => 'open_ended',
            'options' => null,
            'correct_answer' => 'Model-View-Controller is an architectural pattern that separates an application into three main logical components.',
            'marks' => 15
        ]);
    }
}
