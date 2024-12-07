<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if none exists
        $user = User::first() ?? User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'university' => 'Test University',
            'department' => 'Computer Science'
        ]);

        // Create 10 exams
        Exam::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);
    }
}
