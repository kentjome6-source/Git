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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Prevent duplicate likes
            $table->unique(['pet_id', 'user_id']);
            $table->unique(['post_id', 'user_id']);
            
            // Add index for better performance
            $table->index('pet_id');
            $table->index('post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};