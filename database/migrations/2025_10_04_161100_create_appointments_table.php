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
        // This migration is for new installations to create the appointments table
        // For existing installations, the table was created by the consultations migration and then renamed
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                // We store pet info directly on the appointment (no pet_id for request records)
                $table->foreignId('vet_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                
                // Basic appointment info
                $table->string('email')->nullable(); // Email field for appointment contact
                $table->string('status')->default('pending'); // pending, accepted, in_progress, completed, cancelled, rejected
                
                // Owner Information (simplified)
                $table->string('owner_name')->nullable();
                $table->string('owner_phone')->nullable();
                $table->text('owner_address')->nullable();

                // Pet Information (simplified)
                $table->string('pet_name')->nullable();
                // pet_species will store Pet Type (e.g., Dog, Cat)
                $table->string('pet_type')->nullable();
                $table->text('pet_services_received')->nullable();
                
                // Appointment scheduling fields
                $table->dateTime('scheduled_datetime')->nullable();
                
                // Rejection fields
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