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
        Schema::table('lost_founds', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'resolved'])->default('pending')->after('is_resolved');
            $table->foreignId('admin_reviewer_id')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->timestamp('admin_reviewed_at')->nullable()->after('admin_reviewer_id');
            $table->text('admin_notes')->nullable()->after('admin_reviewed_at');
            $table->boolean('is_featured')->default(false)->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('lost_founds', function (Blueprint $table) {
            $table->dropForeign(['admin_reviewer_id']);
            $table->dropColumn(['status', 'admin_reviewer_id', 'admin_reviewed_at', 'admin_notes', 'is_featured']);
        });
    }
};
