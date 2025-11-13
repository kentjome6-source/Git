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
                $table->foreignId('pet_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('vet_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                
                // Basic appointment info
                $table->string('consultation_type')->default('appointment'); // 'chat' or 'appointment'
                $table->string('urgency_level')->default('medium'); // low, medium, high, emergency
                $table->string('status')->default('pending'); // pending, accepted, in_progress, completed, cancelled, rejected
                
                // Owner Information
                $table->string('owner_name')->nullable();
                $table->string('owner_phone')->nullable();
                $table->string('owner_email')->nullable();
                $table->text('owner_address')->nullable();
                
                // Pet Information
                $table->string('pet_name')->nullable();
                $table->string('pet_species')->nullable();
                $table->string('pet_breed')->nullable();
                $table->integer('pet_age_years')->nullable();
                $table->decimal('pet_weight', 8, 2)->nullable();
                $table->string('pet_gender')->nullable();
                
                // Appointment Details
                $table->text('chief_complaint'); // Main reason for appointment
                $table->text('detailed_symptoms')->nullable();
                $table->string('consultation_reason'); // 'routine_checkup', 'illness', 'injury', 'vaccination', 'other'
                // Appointment scheduling fields
                $table->date('appointment_date')->nullable();
                $table->time('appointment_time')->nullable();
                $table->dateTime('scheduled_datetime')->nullable();
                $table->text('additional_concerns')->nullable();
                
                // Duration of Symptoms
                $table->integer('symptom_duration_days')->nullable();
                $table->string('symptom_onset')->nullable();
                $table->text('symptom_progression')->nullable();
                
                // Medical History
                $table->text('allergies')->nullable();
                $table->text('vaccination_history')->nullable();
                $table->text('previous_medical_history')->nullable();
                $table->text('current_medications')->nullable();
                $table->text('previous_treatments')->nullable();
                
                // Rejection fields
                $table->timestamp('rejected_at')->nullable();
                $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
                
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