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
        'responded_at'
    ];
    
    protected $casts = [
        'requested_at' => 'datetime',
        'responded_at' => 'datetime'
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
}