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

    public function definition()
    {
        return [
            'name' => $this->faker->sentence, // Sınav adı
            'description' => $this->faker->paragraph, // Açıklama
            'total_marks' => $this->faker->numberBetween(50, 100), // Toplam puan
            'duration' => $this->faker->numberBetween(30, 180), // Süre (dakika)
            'created_by' => User::factory(), // Sınavı oluşturan kullanıcı
            'status' => $this->faker->randomElement(['active', 'completed', 'scheduled']), // Durum
            'created_at' => $this->faker->dateTimeBetween(Carbon::now()->subMonths(3), Carbon::now()), // Son 3 ay içinde rastgele tarih
        ];
    }
}
