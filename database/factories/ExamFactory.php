<?php

namespace Database\Factories;
use Illuminate\Support\Carbon;
use App\Models\Exam;
use App\Models\User;
use App\Models\University;
use App\Models\Department;

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

    public function definition()
    {
        $university = University::inRandomOrder()->first() ?? University::factory()->create();
        $department = Department::where('university_id', $university->id)->inRandomOrder()->first() 
            ?? Department::factory()->create(['university_id' => $university->id]);

        return [
            'name' => $this->faker->sentence, // Sınav adı
            'description' => $this->faker->paragraph, // Açıklama
            'total_marks' => $this->faker->numberBetween(50, 100), // Toplam puan
            'duration' => $this->faker->numberBetween(30, 180), // Süre (dakika)
            'created_by' => User::factory(), // Sınavı oluşturan kullanıcı
            'status' => $this->faker->randomElement(['active', 'draft', 'completed']), // Durum
            'university_id' => $university->id,
            'department_id' => $department->id,
            'storage_link' => "https://images.unsplash.com/photo-1491841651911-c44c30c34548?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            'year' => $this->faker->numberBetween(2020, 2024),
            'semester' => $this->faker->randomElement(['fall', 'spring', 'summer']),
            'created_at' => $this->faker->dateTimeBetween(Carbon::now()->subMonths(3), Carbon::now()), // Son 3 ay içinde rastgele tarih
        ];
    }
}
