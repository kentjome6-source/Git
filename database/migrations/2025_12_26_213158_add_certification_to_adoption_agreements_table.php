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
        Schema::table('adoption_agreements', function (Blueprint $table) {
            // Admin certificate issuance
            $table->boolean('admin_certificate_issued')->default(false)->after('payment_completed');
            $table->string('admin_certificate_number')->nullable()->after('admin_certificate_issued');
            $table->timestamp('admin_certificate_issued_at')->nullable()->after('admin_certificate_number');
            $table->unsignedBigInteger('admin_issued_by')->nullable()->after('admin_certificate_issued_at');
            
            // Vet final clearance
            $table->boolean('vet_final_clearance')->default(false)->after('admin_issued_by');
            $table->timestamp('vet_final_clearance_date')->nullable()->after('vet_final_clearance');
            $table->text('vet_final_clearance_notes')->nullable()->after('vet_final_clearance_date');
            $table->unsignedBigInteger('vet_final_clearance_by')->nullable()->after('vet_final_clearance_notes');
            
            $table->foreign('admin_issued_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('vet_final_clearance_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_agreements', function (Blueprint $table) {
            $table->dropForeign(['admin_issued_by']);
            $table->dropForeign(['vet_final_clearance_by']);
            
            $table->dropColumn([
                'admin_certificate_issued',
                'admin_certificate_number',
                'admin_certificate_issued_at',
                'admin_issued_by',
                'vet_final_clearance',
                'vet_final_clearance_date',
                'vet_final_clearance_notes',
                'vet_final_clearance_by'
            ]);
        });
    }
};
