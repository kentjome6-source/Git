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
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vet_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

                $table->string('email')->nullable();
                $table->string('status')->default('pending');
                
                $table->string('owner_name')->nullable();
                $table->string('owner_phone')->nullable();
                $table->text('owner_address')->nullable();

                $table->string('pet_name')->nullable();
                $table->string('pet_type')->nullable();
                $table->text('pet_services_received')->nullable();

                $table->dateTime('scheduled_datetime')->nullable();

                $table->timestamp('rejected_at')->nullable();
                $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
                $table->text('rejection_reason')->nullable();
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};