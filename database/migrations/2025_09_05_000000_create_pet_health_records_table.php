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
        Schema::create('pet_health_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('species');
            $table->string('breed')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('condition')->nullable();
            $table->text('medical_notes')->nullable();
            $table->date('diagnosed_at')->nullable();
            $table->string('vaccine_name')->nullable();
            $table->date('date_given')->nullable();
            $table->date('next_due')->nullable();
            $table->string('vaccine_status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_health_records');
    }
};