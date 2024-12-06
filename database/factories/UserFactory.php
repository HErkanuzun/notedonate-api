<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $universities = ['İstanbul Üniversitesi', 'Boğaziçi Üniversitesi', 'ODTÜ', 'İTÜ', 'Yıldız Teknik Üniversitesi'];
        $departments = ['Bilgisayar Mühendisliği', 'Elektrik-Elektronik Mühendisliği', 'Makine Mühendisliği', 'Endüstri Mühendisliği'];
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode(fake()->name()) . '&color=7F9CF5&background=EBF4FF',
            'bio' => fake()->paragraph(),
            'university' => fake()->randomElement($universities),
            'department' => fake()->randomElement($departments),
            'followers' => fake()->numberBetween(0, 1000),
            'following' => fake()->numberBetween(0, 1000),
            'favorites' => json_encode([
                'notes' => [],
                'exams' => [],
                'articles' => []
            ]),
            'current_team_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
