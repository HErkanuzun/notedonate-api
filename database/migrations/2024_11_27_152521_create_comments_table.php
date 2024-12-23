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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->morphs('commentable'); // Bu, commentable_id ve commentable_type sütunlarını oluşturur
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes(); // Yorumları silmek yerine soft delete yapalım
            $table->boolean('is_approved')->default(false); // Onay durumu sütunu

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
