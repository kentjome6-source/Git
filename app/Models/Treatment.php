<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_health_record_id',
        'vet_id',
        'treatment_date',
        'title',
        'medication',
        'dosage',
        'frequency',
        'notes',
    ];

    public function record()
    {
        return $this->belongsTo(PetHealthRecord::class, 'pet_health_record_id');
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }
}
