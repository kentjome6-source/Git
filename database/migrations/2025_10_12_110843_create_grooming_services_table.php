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
        Schema::create('grooming_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shelter_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->foreign('shelter_id')->references('id')->on('shelters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grooming_services');
    }
};