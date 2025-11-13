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
    ];

    protected $casts = [
        'date_lost_found' => 'date',
        'is_resolved' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}