<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroomingService extends Model
{
    protected $fillable = [
        'shelter_id',
        'name',
        'description',
        'price',
        'duration',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_available' => 'boolean'
    ];

    /**
     * Get the shelter that owns this grooming service
     */
    public function shelter()
    {
        return $this->belongsTo(Shelter::class);
    }
}