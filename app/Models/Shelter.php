<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'operating_hours',
        'latitude',
        'longitude',
        'is_active'
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean'
    ];

    /**
     * Scope for active shelters
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the human-readable name for the shelter type
     */
    public function getTypeNameAttribute()
    {
        switch($this->type) {
            case 'pet_shop':
                return 'Pet Shop';
            case 'veterinarian':
                return 'Veterinarian';
            case 'grooming':
                return 'Grooming Service';
            default:
                return ucfirst(str_replace('_', ' ', $this->type));
        }
    }

    /**
     * Get the grooming services for this shelter
     */
    public function groomingServices()
    {
        return $this->hasMany(GroomingService::class);
    }
}