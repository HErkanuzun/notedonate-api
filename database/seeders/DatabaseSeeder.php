<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Note;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'university' => 'Test University',
            'department' => 'Computer Science'
        ]);

        // Create 10 notes for the user
        Note::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);

        // Create 10 exams for the user
        Exam::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);
    }
}
