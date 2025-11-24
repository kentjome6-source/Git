<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'breed',
        'image_path',
        'description',
        'appropriate_food',
        'other_care_details',
    ];

    // Relationship: a Pet belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: a Pet can be adopted by a user
    public function adopter()
    {
        return $this->belongsTo(User::class, 'adopter_id');
    }

    // Relationship: a Pet can have one adoption listing
    public function adoption()
    {
        return $this->hasOne(Adoption::class);
    }

    // Accessor to get the full image URL
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        // Return a default image if no image is uploaded
        return asset('images/pawpatrol.jpg');
    }
}