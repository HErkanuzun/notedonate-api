<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\ExamQuestion;

class ExamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam = Exam::first(); // Varsayılan olarak bir sınav al
    
        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question' => 'What is the capital of France?',
            'question_type' => 'multiple_choice', // Çoktan seçmeli soru
            'options' => json_encode(['A' => 'Paris', 'B' => 'London', 'C' => 'Berlin', 'D' => 'Madrid']),
            'correct_option' => 1, // Paris doğru cevap
        ]);
    
        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question' => 'Explain the theory of relativity.',
            'question_type' => 'open_ended', // Açık uçlu soru
            'options' => null,
            'correct_option' => null, // Açık uçlu soru için doğru cevap yok
        ]);
    
        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question' => 'Is the earth flat?',
            'question_type' => 'true_or_false', // Doğru/Yanlış sorusu
            'options' => null,
            'correct_option' => 1, // Doğru seçenek: 1 (True)
        ]);
    }
}
