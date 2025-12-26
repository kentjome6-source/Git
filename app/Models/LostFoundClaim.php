<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostFoundClaim extends Model
{
    protected $fillable = [
        'lost_found_id',
        'claimer_id',
        'proof_description',
        'proof_images',
        'identification_info',
        'status',
        'admin_reviewer_id',
        'vet_verifier_id',
        'admin_reviewed_at',
        'vet_verified_at',
        'admin_notes',
        'vet_notes',
        'rejection_reason',
        'completed_at',
    ];

    protected $casts = [
        'proof_images' => 'array',
        'admin_reviewed_at' => 'datetime',
        'vet_verified_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function lostFound()
    {
        return $this->belongsTo(LostFound::class);
    }

    public function claimer()
    {
        return $this->belongsTo(User::class, 'claimer_id');
    }

    public function adminReviewer()
    {
        return $this->belongsTo(User::class, 'admin_reviewer_id');
    }

    public function vetVerifier()
    {
        return $this->belongsTo(User::class, 'vet_verifier_id');
    }
}
