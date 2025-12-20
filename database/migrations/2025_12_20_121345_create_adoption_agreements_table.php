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
        Schema::create('adoption_agreements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adoption_request_id');
            $table->unsignedBigInteger('adoption_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('adopter_id');
            $table->text('terms_and_conditions');
            $table->boolean('owner_signed')->default(false);
            $table->boolean('adopter_signed')->default(false);
            $table->timestamp('owner_signed_at')->nullable();
            $table->timestamp('adopter_signed_at')->nullable();
            $table->string('owner_signature')->nullable();
            $table->string('adopter_signature')->nullable();
            $table->text('special_conditions')->nullable();
            $table->decimal('adoption_fee', 8, 2)->default(0);
            $table->boolean('payment_completed')->default(false);
            $table->timestamps();
            
            $table->foreign('adoption_request_id')->references('id')->on('adoption_requests')->onDelete('cascade');
            $table->foreign('adoption_id')->references('id')->on('adoption')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('adopter_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_agreements');
    }
};
