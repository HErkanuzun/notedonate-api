<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type'); // multiple_choice, true_false, etc.
            $table->dateTime('exam_date');
            $table->integer('duration'); // in minutes
            $table->integer('total_questions');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->text('options')->nullable(); // JSON array of options
            $table->string('correct_answer');
            $table->integer('points')->default(1);
            $table->timestamps();
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('score');
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->integer('duration_taken'); // in seconds
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams');
    }
};
