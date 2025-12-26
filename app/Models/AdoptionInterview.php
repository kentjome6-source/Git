<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionInterview extends Model
{
    protected $fillable = [
        'adoption_request_id',
        'interview_type',
        'scheduled_date',
        'conducted_by',
        'interview_notes',
        'passed',
        'completed_at'
    ];
    
    protected $casts = [
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean'
    ];
    
    public function adoptionRequest()
    {
        return $this->belongsTo(AdoptionRequest::class);
    }
    
    public function conductor()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
    
    public function isCompleted()
    {
        return !is_null($this->completed_at);
    }
}
