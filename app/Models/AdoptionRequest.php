<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adoption;
use App\Models\User;

class AdoptionRequest extends Model
{
    protected $table = 'adoption_requests';
    
    protected $fillable = [
        'adoption_id',
        'adopter_id',
        'status',
        'requested_at',
        'responded_at',
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
        'rejection_reason',
        'admin_screened',
        'admin_screening_date',
        'admin_screened_by',
        'admin_screening_notes',
        'admin_screening_rejection',
        'vet_orientation_completed',
        'vet_orientation_date',
        'vet_orientation_by',
        'vet_orientation_notes',
        'owner_approved',
        'owner_approval_date',
        'admin_final_approved',
        'admin_final_approval_date',
        'admin_final_approved_by',
        'admin_approval_notes'
    ];
    
    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
        'has_yard' => 'boolean',
        'landlord_approval' => 'boolean',
        'agree_to_home_visit' => 'boolean',
        'admin_screened' => 'boolean',
        'admin_screening_date' => 'datetime',
        'vet_orientation_completed' => 'boolean',
        'vet_orientation_date' => 'datetime',
        'owner_approved' => 'boolean',
        'owner_approval_date' => 'datetime',
        'admin_final_approved' => 'boolean',
        'admin_final_approval_date' => 'datetime'
    ];
    
    // Relationship: an adoption request belongs to an adoption listing
    public function adoption()
    {
        return $this->belongsTo(Adoption::class);
    }
    
    // Relationship: an adoption request belongs to a user (adopter)
    public function adopter()
    {
        return $this->belongsTo(User::class, 'adopter_id');
    }
    
    // Relationship: adoption request has one agreement
    public function agreement()
    {
        return $this->hasOne(AdoptionAgreement::class);
    }
    
    // Relationship: adoption request has many interviews
    public function interviews()
    {
        return $this->hasMany(AdoptionInterview::class);
    }
    
    // Relationship: admin who screened
    public function adminScreener()
    {
        return $this->belongsTo(User::class, 'admin_screened_by');
    }
    
    // Relationship: vet who conducted orientation
    public function vetOrientator()
    {
        return $this->belongsTo(User::class, 'vet_orientation_by');
    }
    
    // Alias for vetOrientator
    public function vetOrientation()
    {
        return $this->belongsTo(User::class, 'vet_orientation_by');
    }
    
    // Alias for adminScreener
    public function adminScreening()
    {
        return $this->belongsTo(User::class, 'admin_screened_by');
    }
    
    // Check if adoption request is pending
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    // Check if adoption request is approved
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    // Check if adoption request is rejected
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    // Check if application is complete
    public function isComplete()
    {
        return !empty($this->full_name) &&
               !empty($this->email) &&
               !empty($this->phone) &&
               !empty($this->address) &&
               !empty($this->reason_for_adoption);
    }
    
    // Check if needs admin screening
    public function needsAdminScreening()
    {
        return $this->status === 'pending';
    }
    
    // Check if needs vet orientation
    public function needsVetOrientation()
    {
        return $this->status === 'vet_orientation';
    }
    
    // Check if awaiting owner review
    public function awaitingOwnerReview()
    {
        return $this->status === 'owner_review';
    }
}