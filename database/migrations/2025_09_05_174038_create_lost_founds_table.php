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
        Schema::create('lost_founds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['lost', 'found']);
            $table->string('pet_name')->default('Unknown');
            $table->string('pet_type'); // dog, cat, bird, etc.
            $table->string('breed')->nullable();
            $table->string('color')->nullable();
            $table->enum('size', ['small', 'medium', 'large'])->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->text('description');
            $table->string('location');
            $table->date('date_lost_found');
            $table->string('contact_name');
            $table->string('contact_phone', 20);
            $table->string('contact_email')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->timestamps();
            
            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_founds');
    }
};