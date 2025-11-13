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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->text('content');
            $table->timestamps();
            
            // Add foreign key constraint for post_id
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            
            // Add indexes for better performance
            $table->index('pet_id');
            $table->index('post_id');
            
            // Add a check constraint to ensure either pet_id or post_id is set, but not both
            $table->unique(['pet_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};