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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location')->nullable();
            $table->string('type')->default('general'); // general, meeting, deadline, etc.
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->unsignedBigInteger('created_by');
            $table->string('cover_image')->default('https://images.unsplash.com/photo-1733077151496-5e2701fc64eb?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
// Get all event images
$event->images;

// Get all event documents
$event->documents;// Get all event images
$event->images;

// Get all event documents
$event->documents;// Get all event images
$event->images;

// Get all event documents
$event->documents;// Get all event images
$event->images;

// Get all event documents
$event->documents;    }
};
