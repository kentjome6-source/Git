<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'pet_id',
        'post_id',
        'user_id',
    ];
    
    // Relationship: a Like belongs to a pet
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
    
    // Relationship: a Like belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    // Relationship: a Like belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}