<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdoptionRequest;
use App\Models\AdoptionHistory;

class Adoption extends Model
{
    use HasFactory;
    
    protected $table = 'adoption';
    
    protected $fillable = [
        'pet_id',
        'user_id',
        'uploader_type',
        'pet_name',
        'breed',
        'age',
        'gender',
        'description',
        'image_path',
        'is_adopted',
        'listing_status',
        'vet_id',
        'vet_certified',
        'vet_certification_date',
        'vet_health_notes',
        'vet_rejection_reason',
        'admin_approved',
        'admin_approval_date',
        'admin_approved_by',
        'admin_rejection_reason'
    ];
    
    protected $casts = [
        'vet_certified' => 'boolean',
        'admin_approved' => 'boolean',
        'vet_certification_date' => 'datetime',
        'admin_approval_date' => 'datetime',
        'is_adopted' => 'boolean'
    ];
    
    // Relationship: an adoption belongs to a pet
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
    
    // Relationship: an adoption belongs to a user (pet owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship: vet who certified the pet
    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }
    
    // Relationship: admin who approved listing
    public function adminApprover()
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }
    
    // Relationship: an adoption can have many adoption requests
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
    
    // Relationship: an adoption can have one adoption history record
    public function adoptionHistory()
    {
        return $this->hasOne(AdoptionHistory::class);
    }
    
    // Relationship: get the adopter through adoption history
    public function adopter()
    {
        return $this->hasOneThrough(
            User::class,
            AdoptionHistory::class,
            'adoption_id', // Foreign key on adoption_history table
            'id',          // Foreign key on users table
            'id',          // Local key on adoption table
            'adopter_id'   // Local key on adoption_history table
        );
    }
    
    // Get the latest adoption request for this adoption
    public function latestAdoptionRequest()
    {
        return $this->adoptionRequests()->latest()->first();
    }
    
    // Check if the adoption has any pending requests
    public function hasPendingRequest()
    {
        return $this->adoptionRequests()->where('status', 'pending')->exists();
    }
    
    // Check if the adoption has any approved requests
    public function hasApprovedRequest()
    {
        return $this->adoptionRequests()->where('status', 'approved')->exists();
    }
    
    // Get the pending adoption request if exists
    public function pendingRequest()
    {
        return $this->adoptionRequests()->where('status', 'pending')->first();
    }
    
    // Check if the adoption is available (no pending requests, no approved requests, and not adopted)
    public function isAvailable()
    {
        return !$this->is_adopted && !$this->hasPendingRequest() && !$this->hasApprovedRequest();
    }
    
    // Accessor for adoption status
    public function getStatusAttribute()
    {
        if ($this->is_adopted) {
            return 'Adopted';
        }
        
        if ($this->listing_status === 'published' && $this->hasApprovedRequest()) {
            return 'Approved - Pending Completion';
        }
        
        if ($this->listing_status === 'published' && $this->hasPendingRequest()) {
            return 'Pending Approval';
        }
        
        switch ($this->listing_status) {
            case 'vet_review':
                return 'Pending Vet Review';
            case 'vet_rejected':
                return 'Rejected by Veterinarian';
            case 'admin_review':
                return 'Pending Admin Approval';
            case 'admin_rejected':
                return 'Rejected by Admin';
            case 'published':
                return 'Available for Adoption';
            default:
                return 'Under Review';
        }
    }
    
    // Check if listing is published and available
    public function isPublished()
    {
        return $this->listing_status === 'published' && !$this->is_adopted;
    }
    
    // Check if listing needs vet review
    public function needsVetReview()
    {
        return $this->listing_status === 'vet_review';
    }
    
    // Check if listing needs admin review
    public function needsAdminReview()
    {
        return $this->listing_status === 'admin_review';
    }
}