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
        // First, update any existing records with old status values that are not in the new enum
        // Map existing values to the new enum values
        DB::statement("UPDATE adoption_requests SET status = 'pending' WHERE status = 'admin_screening'");
        DB::statement("UPDATE adoption_requests SET status = 'rejected' WHERE status = 'admin_rejected'");
        DB::statement("UPDATE adoption_requests SET status = 'vet_review' WHERE status = 'vet_orientation'");
        
        // Now modify the enum to include all the new values
        DB::statement("ALTER TABLE `adoption_requests` MODIFY COLUMN `status` ENUM('pending', 'admin_review', 'vet_review', 'owner_review', 'owner_approved', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update existing records with newer status values to compatible old values before changing enum
        DB::statement("UPDATE adoption_requests SET status = 'pending' WHERE status = 'admin_review'");
        DB::statement("UPDATE adoption_requests SET status = 'vet_orientation' WHERE status = 'vet_review'");
        DB::statement("UPDATE adoption_requests SET status = 'owner_approved' WHERE status = 'owner_approved'");
        
        // For the down migration, we need to go back to the previous enum that included screening values
        DB::statement("ALTER TABLE `adoption_requests` MODIFY COLUMN `status` ENUM('pending', 'admin_screening', 'admin_rejected', 'vet_orientation', 'owner_review', 'approved', 'rejected') DEFAULT 'pending'");
    }
};