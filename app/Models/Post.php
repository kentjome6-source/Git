<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_path',
    ];
    
    // Specify the route key name for route model binding
    public function getRouteKeyName()
    {
        return 'id';
    }
    
    // Relationship: a Post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship: a Post can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // Relationship: a Post can have many likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    // Check if a user has liked this post
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
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