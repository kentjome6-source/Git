<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the status enum to include all necessary values for the adoption workflow
        DB::statement("ALTER TABLE adoption_requests MODIFY COLUMN status ENUM('pending', 'admin_screening', 'admin_rejected', 'vet_orientation', 'vet_review', 'owner_review', 'owner_approved', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing records with newer status values to compatible old values before changing enum
        DB::statement("UPDATE adoption_requests SET status = 'admin_review' WHERE status = 'admin_screening'");
        DB::statement("UPDATE adoption_requests SET status = 'vet_review' WHERE status = 'vet_orientation'");
        
        // Revert to the previous enum values (without vet_orientation)
        DB::statement("ALTER TABLE adoption_requests MODIFY COLUMN status ENUM('pending', 'admin_review', 'vet_review', 'owner_review', 'owner_approved', 'approved', 'rejected') DEFAULT 'pending'");
    }
};