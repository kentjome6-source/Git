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
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->boolean('owner_approved')->default(false)->after('vet_orientation_notes');
            $table->timestamp('owner_approval_date')->nullable()->after('owner_approved');
            $table->boolean('admin_final_approved')->default(false)->after('admin_screening_rejection');
            $table->timestamp('admin_final_approval_date')->nullable()->after('admin_final_approved');
            $table->unsignedBigInteger('admin_final_approved_by')->nullable()->after('admin_final_approval_date');
            $table->text('admin_approval_notes')->nullable()->after('admin_final_approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn([
                'owner_approved',
                'owner_approval_date',
                'admin_final_approved',
                'admin_final_approval_date',
                'admin_final_approved_by',
                'admin_approval_notes'
            ]);
        });
    }
};
