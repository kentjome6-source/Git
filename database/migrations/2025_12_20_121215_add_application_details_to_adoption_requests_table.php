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
            $table->string('full_name')->after('adopter_id');
            $table->string('email')->after('full_name');
            $table->string('phone')->after('email');
            $table->text('address')->after('phone');
            $table->enum('housing_type', ['house', 'apartment', 'condo', 'other'])->after('address');
            $table->boolean('has_yard')->default(false)->after('housing_type');
            $table->enum('own_or_rent', ['own', 'rent'])->after('has_yard');
            $table->boolean('landlord_approval')->nullable()->after('own_or_rent');
            $table->text('current_pets')->nullable()->after('landlord_approval');
            $table->text('veterinarian_info')->nullable()->after('current_pets');
            $table->text('experience_with_pets')->nullable()->after('veterinarian_info');
            $table->text('reason_for_adoption')->after('experience_with_pets');
            $table->integer('hours_alone')->nullable()->after('reason_for_adoption');
            $table->boolean('agree_to_home_visit')->default(false)->after('hours_alone');
            $table->text('additional_info')->nullable()->after('agree_to_home_visit');
            $table->text('rejection_reason')->nullable()->after('responded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'email',
                'phone',
                'address',
                'housing_type',
                'has_yard',
                'own_or_rent',
                'landlord_approval',
                'current_pets',
                'veterinarian_info',
                'experience_with_pets',
                'reason_for_adoption',
                'hours_alone',
                'agree_to_home_visit',
                'additional_info',
                'rejection_reason'
            ]);
        });
    }
};
