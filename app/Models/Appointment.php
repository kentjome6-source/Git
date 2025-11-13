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
        'consultation_type',
        'urgency_level',
        'status',
        
        // Owner Information
        'owner_name',
        'owner_phone',
        'owner_email',
        'owner_address',
        
        // Pet Information
        'pet_name',
        'pet_species',
        'pet_breed',
        'pet_age_years',
        // Removed pet_age_months as it doesn't exist in the database
        'pet_weight',
        'pet_gender',
        
        // Appointment Details
        'chief_complaint',
        'detailed_symptoms',
        'consultation_reason',
        'appointment_date',
        'appointment_time',
        'scheduled_datetime',
        'additional_concerns',
        
        // Duration of Symptoms
        'symptom_duration_days',
        'symptom_onset',
        'symptom_progression',
        
        // Previous Medications / Treatments
        'current_medications',
        'previous_treatments',
        'allergies',
        'vaccination_history',
        'previous_medical_history',
        
        // Rejection fields
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'pet_weight' => 'decimal:2',
        'rejected_at' => 'datetime',
        'appointment_date' => 'date',
        'appointment_time' => 'string', // Keep as string since it's stored as TIME in database
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

    // Helper methods
    public function getUrgencyBadgeClass(): string
    {
        return match($this->urgency_level) {
            'low' => 'badge-success',
            'medium' => 'badge-warning',
            'high' => 'badge-danger',
            'emergency' => 'badge-dark',
            default => 'badge-secondary'
        };
    }

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
        $age = '';
        if ($this->pet_age_years) {
            $age .= $this->pet_age_years . ' year' . ($this->pet_age_years > 1 ? 's' : '');
        }
        // Removed pet_age_months reference as it doesn't exist in the database
        return $age ?: 'Age not specified';
    }
}