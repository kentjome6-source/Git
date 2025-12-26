<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vetshop extends Model
{
    protected $table = 'vet_shop';

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
        'animal_types',
        'latitude',
        'longitude',
        'is_active'
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'animal_types' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean'
    ];

    /**
     * Scope for active vetshops
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByAnimalType($query, $animalType)
    {
        return $query->whereJsonContains('animal_types', $animalType);
    }

    /**
     * Get the human-readable name for the vetshop type
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

    public function getAnimalTypesStringAttribute()
    {
        if (empty($this->animal_types)) {
            return 'Not specified';
        }
        
        return implode(', ', array_map('ucfirst', $this->animal_types));
    }

    /**
     * Get the grooming services for this vetshop
     */
    // public function groomingServices()
    // {
    //     return $this->hasMany(GroomingService::class);
    // }

    public function vets(){
        return $this->hasMany(User::class);
    }
}