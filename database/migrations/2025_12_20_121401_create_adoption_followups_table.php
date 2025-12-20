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
        Schema::create('adoption_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adoption_history_id');
            $table->enum('followup_type', ['1_week', '1_month', '3_months', '6_months', '1_year']);
            $table->date('scheduled_date');
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->enum('pet_status', ['excellent', 'good', 'fair', 'poor', 'returned'])->nullable();
            $table->text('health_status')->nullable();
            $table->text('behavioral_status')->nullable();
            $table->boolean('requires_attention')->default(false);
            $table->timestamps();
            
            $table->foreign('adoption_history_id')->references('id')->on('adoption_history')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_followups');
    }
};
