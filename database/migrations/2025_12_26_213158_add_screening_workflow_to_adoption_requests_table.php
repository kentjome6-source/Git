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
        Schema::table('adoption_requests', function (Blueprint $table) {
            // Update status enum to include new workflow steps
            DB::statement("ALTER TABLE adoption_requests MODIFY COLUMN status ENUM('pending', 'admin_screening', 'admin_rejected', 'vet_orientation', 'owner_review', 'approved', 'rejected') DEFAULT 'pending'");
            
            // Admin screening
            $table->boolean('admin_screened')->default(false)->after('status');
            $table->timestamp('admin_screening_date')->nullable()->after('admin_screened');
            $table->unsignedBigInteger('admin_screened_by')->nullable()->after('admin_screening_date');
            $table->text('admin_screening_notes')->nullable()->after('admin_screened_by');
            $table->text('admin_screening_rejection')->nullable()->after('admin_screening_notes');
            
            // Vet orientation
            $table->boolean('vet_orientation_completed')->default(false)->after('admin_screening_rejection');
            $table->timestamp('vet_orientation_date')->nullable()->after('vet_orientation_completed');
            $table->unsignedBigInteger('vet_orientation_by')->nullable()->after('vet_orientation_date');
            $table->text('vet_orientation_notes')->nullable()->after('vet_orientation_by');
            
            $table->foreign('admin_screened_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vet_orientation_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropForeign(['admin_screened_by']);
            $table->dropForeign(['vet_orientation_by']);
            
            $table->dropColumn([
                'admin_screened',
                'admin_screening_date',
                'admin_screened_by',
                'admin_screening_notes',
                'admin_screening_rejection',
                'vet_orientation_completed',
                'vet_orientation_date',
                'vet_orientation_by',
                'vet_orientation_notes'
            ]);
            
            // Update existing records with newer status values to compatible old values before changing enum
            DB::statement("UPDATE adoption_requests SET status = 'pending' WHERE status IN ('admin_screening', 'vet_orientation', 'owner_review')");
            DB::statement("UPDATE adoption_requests SET status = 'rejected' WHERE status = 'admin_rejected'");
            DB::statement("UPDATE adoption_requests SET status = 'approved' WHERE status IN ('approved', 'rejected')");
            
            // Revert status enum
            DB::statement("ALTER TABLE adoption_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        });
    }
};
