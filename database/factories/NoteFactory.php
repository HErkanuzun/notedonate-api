<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\University;
use App\Models\Department;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Şimdiki tarihten itibaren 3 ay öncesine kadar bir tarih oluştur
        $createdAt = $this->faker->dateTimeBetween('-3 months', 'now');
        $updatedAt = Carbon::instance($createdAt)->addDays(rand(0, 30)); // created_at sonrası rastgele bir gün
        
        $university = University::inRandomOrder()->first() ?? University::factory()->create();
        $department = Department::where('university_id', $university->id)->inRandomOrder()->first() 
            ?? Department::factory()->create(['university_id' => $university->id]);

        return [
            'title' => $this->faker->sentence(), // Rastgele bir başlık
            'content' => $this->faker->paragraphs(3, true), // Rastgele 3 paragraftan oluşan içerik
            'storage_link' => "https://images.unsplash.com/photo-1506962240359-bd03fbba0e3d?q=80&w=2665&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D", // Rastgele veya boş bırakılabilir URL
            'viewer' => $this->faker->numberBetween(0, 5000), // 0 ile 5000 arasında rastgele bir görüntüleme sayısı
            'like' => $this->faker->numberBetween(0, 500), // 0 ile 500 arasında rastgele beğeni sayısı
            'created_by' => 1,
            'university_id' => $university->id,
            'department_id' => $department->id,
            'year' => $this->faker->numberBetween(2020, 2024),
            'semester' => $this->faker->randomElement(['fall', 'spring', 'summer']),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
