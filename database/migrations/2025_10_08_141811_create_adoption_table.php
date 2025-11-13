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
        Schema::create('adoption', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->unsignedBigInteger('user_id'); // Pet owner
            $table->string('uploader_type')->default('user');
            $table->string('pet_name');
            $table->string('breed')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_adopted')->default(false);
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption');
    }
};