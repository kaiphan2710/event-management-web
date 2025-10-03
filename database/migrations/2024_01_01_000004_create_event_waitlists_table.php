<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the event_waitlists table for the waitlist system
 * Manages user positions on event waitlists when events are full
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->integer('position'); // Position in the waitlist queue
            $table->timestamp('joined_at')->useCurrent();
            $table->enum('status', ['active', 'removed', 'promoted'])->default('active');
            $table->timestamps();
            
            // Ensure a user cannot be on the same event's waitlist more than once
            $table->unique(['user_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_waitlists');
    }
};
