<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            if (!Schema::hasColumn('notes', 'university_id')) {
                $table->foreignId('university_id')->nullable()->constrained();
            }
            if (!Schema::hasColumn('notes', 'department_id')) {
                $table->foreignId('department_id')->nullable()->constrained();
            }
            if (!Schema::hasColumn('notes', 'year')) {
                $table->integer('year')->nullable();
            }
            if (!Schema::hasColumn('notes', 'semester')) {
                $table->enum('semester', ['fall', 'spring', 'summer'])->nullable();
            }
        });

        // Skip exams table modifications as they are handled in another migration
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'university_id')) {
                $table->dropForeign(['university_id']);
            }
            if (Schema::hasColumn('notes', 'department_id')) {
                $table->dropForeign(['department_id']);
            }
            $table->dropColumn(['university_id', 'department_id', 'year', 'semester']);
        });
    }
};
