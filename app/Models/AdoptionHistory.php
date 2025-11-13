<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adoption;
use App\Models\User;

class AdoptionHistory extends Model
{
    protected $table = 'adoption_history';
    
    protected $fillable = [
        'adoption_id',
        'uploader_id',
        'adopter_id',
        'adopted_at'
    ];
    
    protected $casts = [
        'adopted_at' => 'datetime'
    ];
    
    // Relationship: adoption history belongs to an adoption listing
    public function adoption()
    {
        return $this->belongsTo(Adoption::class);
    }
    
    // Relationship: adoption history belongs to the uploader (original owner)
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
    
    // Relationship: adoption history belongs to the adopter (new owner)
    public function adopter()
    {
        return $this->belongsTo(User::class, 'adopter_id');
    }
}