<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location')->nullable();
            $table->string('type'); // online, in-person
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed
            $table->integer('max_participants')->nullable();
            $table->timestamps();
        });

        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('registered'); // registered, attended, cancelled
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('events');
    }
};
