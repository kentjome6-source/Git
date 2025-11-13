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
        'uploader_type', // 'vet' or 'user'
        'pet_name',
        'breed',
        'age',
        'gender',
        'description',
        'image_path',
        'is_adopted'
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
        
        if ($this->hasApprovedRequest()) {
            return 'Approved - Pending Completion';
        }
        
        if ($this->hasPendingRequest()) {
            return 'Pending Approval';
        }
        
        return 'Available';
    }
}