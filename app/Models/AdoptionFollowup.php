<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionFollowup extends Model
{
    protected $fillable = [
        'adoption_history_id',
        'followup_type',
        'scheduled_date',
        'completed',
        'completed_at',
        'notes',
        'pet_status',
        'health_status',
        'behavioral_status',
        'requires_attention'
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'requires_attention' => 'boolean'
    ];
    
    public function adoptionHistory()
    {
        return $this->belongsTo(AdoptionHistory::class);
    }
    
    public function isOverdue()
    {
        return !$this->completed && $this->scheduled_date < now();
    }
    
    public function isDue()
    {
        return !$this->completed && $this->scheduled_date->isToday();
    }
}
