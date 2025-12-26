<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_found_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_found_id')->constrained('lost_founds')->onDelete('cascade');
            $table->foreignId('claimer_id')->constrained('users')->onDelete('cascade');
            $table->text('proof_description');
            $table->json('proof_images')->nullable();
            $table->string('identification_info')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('admin_reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vet_verifier_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('admin_reviewed_at')->nullable();
            $table->timestamp('vet_verified_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('vet_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_claims');
    }
};
