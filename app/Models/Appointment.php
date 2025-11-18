<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointments';

    protected $fillable = [
        'user_id',
        'pet_id',
        'vet_id',
        'owner_name',
        'owner_phone',
        'email',
        'owner_address',
        'status',
        'pet_name',
        'pet_type',
        'pet_services_received',
        'scheduled_datetime',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
        'approved_at' => 'datetime',
        'scheduled_datetime' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function vet(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'accepted' => 'badge-info',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'rejected' => 'badge-dark',
            default => 'badge-secondary'
        };
    }

    public function getPetAgeString(): string
    {
        return $this->pet_name ? $this->pet_name : 'Pet not specified';
    }

    /**
     * Get the valid pet types
     *
     * @return array
     */
    public static function getValidPetTypes(): array
    {
        return ['Dog', 'Cat'];
    }

    /**
     * Get the predefined pet services
     *
     * @return array
     */
    public static function getPredefinedServices(): array
    {
        return [
            'Deworming',
            'Vaccination',
            'Tick and Flea Prevention'
        ];
    }
}