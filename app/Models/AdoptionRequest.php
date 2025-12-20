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
        'rejection_reason'
    ];
    
    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime',
        'has_yard' => 'boolean',
        'landlord_approval' => 'boolean',
        'agree_to_home_visit' => 'boolean'
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
}