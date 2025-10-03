<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the users table for both attendees and organisers
 * Includes privacy policy consent mechanism as required
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('user_type', ['attendee', 'organiser'])->default('attendee');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Privacy policy and terms consent mechanism
            $table->boolean('privacy_policy_agreed')->default(false);
            $table->boolean('terms_agreed')->default(false);
            $table->timestamp('agreed_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
