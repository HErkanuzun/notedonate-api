<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Exam;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $commentableTypes = [
            Article::class,
            Event::class,
            Exam::class,
            Note::class,
        ];

        $commentableType = $this->faker->randomElement($commentableTypes);
        $commentable = $commentableType::factory()->create();

        return [
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'commentable_id' => $commentable->id,
            'commentable_type' => $commentableType,
            'parent_id' => null,
        ];
    }

    /**
     * Configure the comment as a reply to another comment.
     */
    public function asReply(): static
    {
        return $this->state(function (array $attributes) {
            $parentComment = Comment::factory()->create([
                'commentable_id' => $attributes['commentable_id'],
                'commentable_type' => $attributes['commentable_type'],
            ]);

            return [
                'parent_id' => $parentComment->id,
            ];
        });
    }
}
