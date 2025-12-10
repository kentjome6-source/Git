<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'pet_name',
        'pet_type',
        'is_active',

        'profile_picture_path', // Add profile picture path
        'certificate_path',     // Add certificate path for veterinarians
        'is_verified_vet',      // Add verification status for veterinarians
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_verified_vet' => 'boolean',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the pets for the user.
     */
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Get the adoption listings created by the user.
     */
    public function adoptions()
    {
        return $this->hasMany(Adoption::class);
    }

    /**
     * Get the pets adopted by the user.
     */
    public function adoptedPets()
    {
        return $this->hasMany(Pet::class, 'adopter_id');
    }

    /**
     * Get the appointments for the user.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    /**
     * Get the appointments handled by the veterinarian.
     */
    public function vetAppointments()
    {
        return $this->hasMany(Appointment::class, 'vet_id');
    }

    /**
     * Get the comments made by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Get the posts created by the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Get the likes made by the user.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages()
    {
        return $this->hasMany(ChatMessage::class, 'recipient_id');
    }

    /**
     * Scope a query to only include legitimate users.
     */
    public function scopeLegitimate($query)
    {
        // Exclude test/sample accounts by filtering out emails from example domains
        // and common test user names
        return $query->where('is_active', true)
                    ->whereNotIn('role', ['test'])
                    ->where('email', 'not like', '%example.com')
                    ->where('email', 'not like', '%test.com')
                    ->whereNotIn('name', ['John Doe', 'Jane Smith', 'Mike Johnson']);
    }
    
    /**
     * Get the URL of the user's profile picture.
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture_path) {
            return asset('storage/' . $this->profile_picture_path);
        }
        
        return asset('images/default-user-avatar.png');
    }

    /**
     * Get the URL of the veterinarian's certificate.
     */
    public function getCertificateUrlAttribute()
    {
        if ($this->certificate_path) {
            return asset('storage/' . $this->certificate_path);
        }
        
        return null;
    }

    /**
     * Check if the user is a verified veterinarian.
     */
    public function isVerifiedVet()
    {
        return $this->role === 'vet' && (bool) $this->is_verified_vet;
    }
}
