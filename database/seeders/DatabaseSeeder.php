<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\University;
use App\Models\Department;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Note;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kullanıcılar
        User::factory(5)->create();

        // Üniversiteler ve Bölümler
        $universities = University::factory(5)->create();
        
        foreach ($universities as $university) {
            Department::factory(3)->create([
                'university_id' => $university->id
            ]);
        }

        // Makale Kategorileri ve Makaleler
        ArticleCategory::factory(5)->create();
        
        // Makaleleri oluştur ve kategorilere ata
        Article::factory(15)->create()->each(function ($article) {
            // Her makaleye rastgele bir kategori ata
            $category = ArticleCategory::inRandomOrder()->first();
            if ($category) {
                $article->categories()->attach($category->id);
            }
        });

        // Notlar
        Note::factory(20)->create()->each(function ($note) {
            $department = Department::inRandomOrder()->first();
            $note->update([
                'university_id' => $department->university_id,
                'department_id' => $department->id,
                'year' => rand(2020, 2024),
                'semester' => ['fall', 'spring', 'summer'][rand(0, 2)]
            ]);
        });

        // Sınavlar ve Sınav Soruları
        Exam::factory(15)->create()->each(function ($exam) {
            $department = Department::inRandomOrder()->first();
            $exam->update([
                'university_id' => $department->university_id,
                'department_id' => $department->id,
                'year' => rand(2020, 2024),
                'semester' => ['fall', 'spring', 'summer'][rand(0, 2)]
            ]);
            
            // Her sınav için 3-5 arası soru
            ExamQuestion::factory(rand(3, 5))->create([
                'exam_id' => $exam->id
            ]);
        });

        // Etkinlikler
        Event::factory(10)->create();

        // Yorumlar (farklı modeller için)
        $commentableTypes = [
            Article::class => Article::pluck('id')->toArray(),
            Note::class => Note::pluck('id')->toArray(),
            Exam::class => Exam::pluck('id')->toArray(),
            Event::class => Event::pluck('id')->toArray(),
        ];

        foreach ($commentableTypes as $type => $ids) {
            foreach ($ids as $id) {
                // Her öğe için 0-3 arası yorum
                $commentCount = rand(0, 3);
                if ($commentCount > 0) {
                    Comment::factory($commentCount)->create([
                        'commentable_type' => $type,
                        'commentable_id' => $id,
                        'user_id' => User::inRandomOrder()->first()->id
                    ]);
                }
            }
        }

        // Ayarlar
        Setting::factory(10)->create();
    }
}
