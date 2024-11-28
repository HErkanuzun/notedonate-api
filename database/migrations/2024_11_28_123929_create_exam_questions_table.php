<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id'); // Sınavın ID'si
            $table->text('question'); // Soru metni
            $table->string('question_type', 50); // Soru tipi (çoktan seçmeli, açık uçlu, vb.)
            $table->json('options')->nullable(); // Cevap seçenekleri (varsa, çoktan seçmeli sorular için)
            $table->integer('correct_option')->nullable(); // Doğru seçenek (çoktan seçmeli sorular için)
            $table->timestamps();

            // Foreign key: exam_id, exam tablosundaki id ile ilişkilendirilecek
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        
        
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
