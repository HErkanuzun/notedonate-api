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
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->renameColumn('correct_option', 'correct_answer');
            $table->integer('marks')->default(0)->after('correct_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->renameColumn('correct_answer', 'correct_option');
            $table->dropColumn('marks');
        });
    }
};
