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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Ayar anahtarı
            $table->text('value'); // Ayar değeri
            $table->string('group')->default('general'); // Ayar grubu (genel, email, görünüm vb.)
            $table->string('type')->default('text'); // Değer tipi (text, boolean, json, vb.)
            $table->text('description')->nullable(); // Ayar açıklaması
            $table->boolean('is_public')->default(true); // Genel görünürlük
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
