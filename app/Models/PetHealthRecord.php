<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetHealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'age',
        'weight',
        'condition',
        'medical_notes',
        'diagnosed_at',
        'vaccine_name',
        'date_given',
        'next_due',
        'vaccine_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Treatments / prescriptions associated with this health record.
     */
    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }
}