<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionAgreement extends Model
{
    protected $fillable = [
        'adoption_request_id',
        'adoption_id',
        'owner_id',
        'adopter_id',
        'terms_and_conditions',
        'owner_signed',
        'adopter_signed',
        'owner_signed_at',
        'adopter_signed_at',
        'owner_signature',
        'adopter_signature',
        'special_conditions',
        'adoption_fee',
        'payment_completed'
    ];
    
    protected $casts = [
        'owner_signed' => 'boolean',
        'adopter_signed' => 'boolean',
        'payment_completed' => 'boolean',
        'owner_signed_at' => 'datetime',
        'adopter_signed_at' => 'datetime',
        'adoption_fee' => 'decimal:2'
    ];
    
    public function adoptionRequest()
    {
        return $this->belongsTo(AdoptionRequest::class);
    }
    
    public function adoption()
    {
        return $this->belongsTo(Adoption::class);
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function adopter()
    {
        return $this->belongsTo(User::class, 'adopter_id');
    }
    
    public function isFullySigned()
    {
        return $this->owner_signed && $this->adopter_signed;
    }
    
    public function isReadyForCompletion()
    {
        return $this->isFullySigned() && $this->payment_completed;
    }
}
