<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Storage;

class StorageFactory extends Factory
{
    protected $model = Storage::class;

    public function definition(): array
    {
        return [
            'file_path' => 'default.jpg',
            'type' => 'image'
        ];
    }

    public function event()
    {
        return $this->state(function (array $attributes) {
            return [
                'file_path' => 'events/default.jpg',
                'type' => 'image'
            ];
        });
    }

    public function note()
    {
        return $this->state(function (array $attributes) {
            return [
                'file_path' => 'notes/default.jpg',
                'type' => 'image'
            ];
        });
    }

    public function article()
    {
        return $this->state(function (array $attributes) {
            return [
                'file_path' => 'articles/default.jpg',
                'type' => 'image'
            ];
        });
    }
}
