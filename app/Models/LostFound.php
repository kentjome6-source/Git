<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostFound extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'pet_name',
        'pet_type',
        'breed',
        'color',
        'size',
        'age',
        'gender',
        'description',
        'location',
        'latitude',
        'longitude',
        'date_lost_found',
        'contact_name',
        'contact_phone',
        'contact_email',
        'image_path',
        'is_resolved',
        'status',
        'admin_reviewer_id',
        'admin_reviewed_at',
        'admin_notes',
        'is_featured',
    ];

    protected $casts = [
        'date_lost_found' => 'date',
        'is_resolved' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'is_featured' => 'boolean',
        'admin_reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adminReviewer()
    {
        return $this->belongsTo(User::class, 'admin_reviewer_id');
    }

    public function claims()
    {
        return $this->hasMany(LostFoundClaim::class);
    }

    public function matchesAsLost()
    {
        return $this->hasMany(LostFoundMatch::class, 'lost_id');
    }

    public function matchesAsFound()
    {
        return $this->hasMany(LostFoundMatch::class, 'found_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('is_resolved', false)->where('status', 'approved');
    }
}