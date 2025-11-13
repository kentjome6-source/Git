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
        Schema::create('adoption_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adoption_id');
            $table->unsignedBigInteger('uploader_id');
            $table->unsignedBigInteger('adopter_id');
            $table->timestamp('adopted_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('adoption_id')->references('id')->on('adoption')->onDelete('cascade');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('adopter_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('adoption_id');
            $table->index('uploader_id');
            $table->index('adopter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_history');
    }
};