<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lost_found_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_id')->constrained('lost_founds')->onDelete('cascade');
            $table->foreignId('found_id')->constrained('lost_founds')->onDelete('cascade');
            $table->integer('match_score')->default(0);
            $table->json('match_details')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'confirmed', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lost_found_matches');
    }
};
