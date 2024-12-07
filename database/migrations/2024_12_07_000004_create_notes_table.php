<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('file_path')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->string('subject');
            $table->string('grade_level')->nullable();
            $table->integer('downloads')->default(0);
            $table->integer('likes')->default(0);
            $table->timestamps();
        });

        Schema::create('note_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('note_tag', function (Blueprint $table) {
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('note_tags')->onDelete('cascade');
            $table->primary(['note_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('note_tag');
        Schema::dropIfExists('note_tags');
        Schema::dropIfExists('notes');
    }
};
