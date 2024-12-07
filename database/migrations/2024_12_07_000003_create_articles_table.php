<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('cover_image')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->timestamps();
        });

        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('article_category', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('article_categories')->onDelete('cascade');
            $table->primary(['article_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('articles');
    }
};
