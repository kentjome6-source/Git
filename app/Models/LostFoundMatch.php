<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostFoundMatch extends Model
{
    protected $fillable = [
        'lost_id',
        'found_id',
        'match_score',
        'match_details',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
    ];

    protected $casts = [
        'match_details' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function lostPet()
    {
        return $this->belongsTo(LostFound::class, 'lost_id');
    }

    public function foundPet()
    {
        return $this->belongsTo(LostFound::class, 'found_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
