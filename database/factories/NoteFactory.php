<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;



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
        return [
            'title' => $this->faker->sentence(), // Rastgele bir başlık
            'content' => $this->faker->paragraphs(1000, true), // Rastgele 3 paragraftan oluşan içerik
            'storage_link' => $this->faker->optional()->url(), // Rastgele veya boş bırakılabilir URL
            'viewer' => $this->faker->numberBetween(0, 5000), // 0 ile 1000 arasında rastgele bir görüntüleme sayısı
            'like' => $this->faker->numberBetween(0, 500), // 0 ile 500 arasında rastgele beğeni sayısı
            'created_at' =>$createdAt , // Şu anki zaman
            'updated_at' =>$updatedAt , // Şu anki zaman

        ];
    }
}
