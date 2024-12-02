<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('exams', 'created_by')) {
                $table->dropForeign(['created_by']);
            }

            // Add new columns
            if (!Schema::hasColumn('exams', 'university_id')) {
                $table->foreignId('university_id')->constrained();
            }
            
            // Update status enum
            if (Schema::hasColumn('exams', 'status')) {
                $table->dropColumn('status');
            }
            $table->enum('status', ['active', 'draft', 'completed'])->default('draft');
            
            // Add semester enum
            if (Schema::hasColumn('exams', 'semester')) {
                $table->dropColumn('semester');
            }
            $table->enum('semester', ['fall', 'spring', 'summer'])->nullable();
            
            // Add back the created_by foreign key
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // Drop new columns
            if (Schema::hasColumn('exams', 'university_id')) {
                $table->dropForeign(['university_id', 'created_by']);
                $table->dropColumn(['university_id']);
            }
            
            // Restore old columns
            if (!Schema::hasColumn('exams', 'university')) {
                $table->string('university')->nullable();
            }
            if (!Schema::hasColumn('exams', 'department')) {
                $table->string('department')->nullable();
            }
            
            // Restore old status enum
            if (Schema::hasColumn('exams', 'status')) {
                $table->dropColumn('status');
            }
            $table->enum('status', ['active', 'completed', 'scheduled'])->default('scheduled');
            
            // Restore old semester column
            if (Schema::hasColumn('exams', 'semester')) {
                $table->dropColumn('semester');
            }
            $table->string('semester')->nullable();
            
            // Add back the created_by foreign key
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
