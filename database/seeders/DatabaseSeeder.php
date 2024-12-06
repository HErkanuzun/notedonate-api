<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Article;
use App\Models\Note;
use App\Models\Event;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users first
        \App\Models\User::factory(10)->create();

        // Create settings
        \App\Models\Setting::factory(5)->create();

        // Create articles
        \App\Models\Article::factory(20)->create();

        // Create notes
        \App\Models\Note::factory(30)->create();

        // Create events
        \App\Models\Event::factory(15)->create();

        // Create exams with questions
        \App\Models\Exam::factory(10)
            ->has(\App\Models\ExamQuestion::factory()->count(5))
            ->create();

        // Create comments for articles and notes
        \App\Models\Comment::factory(50)->create();
    }
}
