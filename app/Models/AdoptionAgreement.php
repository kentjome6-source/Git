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
        'payment_completed',
        'admin_certificate_issued',
        'admin_certificate_number',
        'admin_certificate_issued_at',
        'admin_issued_by',
        'vet_final_clearance',
        'vet_final_clearance_date',
        'vet_final_clearance_notes',
        'vet_final_clearance_by'
    ];
    
    protected $casts = [
        'owner_signed' => 'boolean',
        'adopter_signed' => 'boolean',
        'payment_completed' => 'boolean',
        'owner_signed_at' => 'datetime',
        'adopter_signed_at' => 'datetime',
        'adoption_fee' => 'decimal:2',
        'admin_certificate_issued' => 'boolean',
        'admin_certificate_issued_at' => 'datetime',
        'vet_final_clearance' => 'boolean',
        'vet_final_clearance_date' => 'datetime'
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
    
    public function adminIssuer()
    {
        return $this->belongsTo(User::class, 'admin_issued_by');
    }
    
    public function vetClearanceProvider()
    {
        return $this->belongsTo(User::class, 'vet_final_clearance_by');
    }
    
    public function isFullySigned()
    {
        return $this->owner_signed && $this->adopter_signed;
    }
    
    public function isReadyForCompletion()
    {
        return $this->isFullySigned() && 
               $this->payment_completed && 
               $this->admin_certificate_issued && 
               $this->vet_final_clearance;
    }
    
    public function needsAdminCertificate()
    {
        return $this->isFullySigned() && !$this->admin_certificate_issued;
    }
    
    public function needsVetClearance()
    {
        return $this->admin_certificate_issued && !$this->vet_final_clearance;
    }
}
