<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;
    protected static $counter = 1;

    public function definition(): array
    {
        $departments = [
            'Bilgisayar Mühendisliği',
            'Elektrik-Elektronik Mühendisliği',
            'Makine Mühendisliği',
            'İşletme',
            'Ekonomi',
            'Psikoloji',
            'Tıp',
            'Hukuk',
            'Mimarlık',
            'Matematik',
            'Fizik',
            'Kimya',
            'Biyoloji',
            'Endüstri Mühendisliği',
            'İnşaat Mühendisliği'
        ];
        
        $name = $departments[static::$counter % count($departments)];
        $slug = 'department-' . static::$counter;
        static::$counter++;

        return [
            'university_id' => University::factory(),
            'name' => $name,
            'slug' => $slug,
            'is_active' => true,
        ];
    }
}
