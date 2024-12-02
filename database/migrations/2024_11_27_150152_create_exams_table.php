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
        Schema::create('exams', function (Blueprint $table) {
            $table->id(); // Otomatik artan id
            $table->string('name', 255); // Sınav adı
            $table->text('description')->nullable(); // Açıklama
            $table->integer('total_marks'); // Toplam puan
            $table->integer('duration'); // Süre (dakika cinsinden)
            $table->unsignedBigInteger('created_by'); // Oluşturan kullanıcı ID'si
            $table->unsignedBigInteger('university_id'); // 
            $table->text('storage_link')->nullable();
            $table->unsignedBigInteger('department_id'); // 
            $table->integer('year')->nullable(); // 
            $table->enum('semester', ['fall', 'spring', 'summer'])->nullable(); // 
            $table->enum('status', ['active', 'completed', 'scheduled'])->default('scheduled'); // Durum
            $table->timestamps(); // Oluşturulma ve güncellenme zamanları

            // Foreign key relationship with users table (users tablosu var varsayılıyor)
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id(); // Otomatik artan id
            $table->unsignedBigInteger('user_id'); // Kullanıcı ID'si
            $table->unsignedBigInteger('exam_id'); // Sınav ID'si
            $table->integer('marks_obtained'); // Alınan puan
            $table->enum('status', ['passed', 'failed'])->default('failed'); // Sonuç durumu
            $table->timestamps(); // Oluşturulma ve güncellenme zamanları

            // Foreign key relationships
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exams');
    }
};
