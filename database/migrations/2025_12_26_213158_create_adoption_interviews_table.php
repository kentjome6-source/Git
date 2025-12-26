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
        Schema::create('adoption_interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adoption_request_id');
            $table->enum('interview_type', ['phone', 'video', 'in_person', 'home_visit']);
            $table->datetime('scheduled_date');
            $table->unsignedBigInteger('conducted_by')->nullable();
            $table->text('interview_notes')->nullable();
            $table->boolean('passed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('adoption_request_id')->references('id')->on('adoption_requests')->onDelete('cascade');
            $table->foreign('conducted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_interviews');
    }
};
