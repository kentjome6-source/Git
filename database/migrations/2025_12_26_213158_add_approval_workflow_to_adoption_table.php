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
        Schema::table('adoption', function (Blueprint $table) {
            $table->enum('listing_status', [
                'vet_review', 
                'vet_rejected',
                'admin_review', 
                'admin_rejected',
                'published', 
                'adopted'
            ])->default('vet_review')->after('is_adopted');
            
            $table->unsignedBigInteger('vet_id')->nullable()->after('user_id');
            $table->boolean('vet_certified')->default(false)->after('listing_status');
            $table->timestamp('vet_certification_date')->nullable()->after('vet_certified');
            $table->text('vet_health_notes')->nullable()->after('vet_certification_date');
            $table->text('vet_rejection_reason')->nullable()->after('vet_health_notes');
            
            $table->boolean('admin_approved')->default(false)->after('vet_rejection_reason');
            $table->timestamp('admin_approval_date')->nullable()->after('admin_approved');
            $table->unsignedBigInteger('admin_approved_by')->nullable()->after('admin_approval_date');
            $table->text('admin_rejection_reason')->nullable()->after('admin_approved_by');
            
            $table->foreign('vet_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption', function (Blueprint $table) {
            $table->dropForeign(['vet_id']);
            $table->dropForeign(['admin_approved_by']);
            
            $table->dropColumn([
                'listing_status',
                'vet_id',
                'vet_certified',
                'vet_certification_date',
                'vet_health_notes',
                'vet_rejection_reason',
                'admin_approved',
                'admin_approval_date',
                'admin_approved_by',
                'admin_rejection_reason'
            ]);
        });
    }
};
