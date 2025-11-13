<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_health_record_id')->constrained('pet_health_records')->onDelete('cascade');
            $table->foreignId('vet_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('treatment_date')->nullable();
            $table->string('title')->nullable();
            $table->string('medication')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};