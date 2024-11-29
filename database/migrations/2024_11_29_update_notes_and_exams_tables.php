<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable()->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->integer('year')->nullable();
            $table->enum('semester', ['fall', 'spring', 'summer'])->nullable();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable()->constrained();
            $table->foreignId('department_id')->nullable()->constrained();
            $table->integer('year')->nullable();
            $table->enum('semester', ['fall', 'spring', 'summer'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['university_id', 'department_id', 'year', 'semester']);
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['university_id', 'department_id', 'year', 'semester']);
        });
    }
};
