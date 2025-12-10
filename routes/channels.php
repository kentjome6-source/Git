<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Appointment;
use App\Models\User;

// Chat channels authorization
Broadcast::channel('chat.{minUserId}.{maxUserId}', function ($user, $minUserId, $maxUserId) {
    // Users can only join channels where they are either the sender or receiver
    return $user->id == $minUserId || $user->id == $maxUserId;
});

// User channel authorization
Broadcast::channel('users.{userId}', function ($user, $userId) {
    return $user->id == $userId;
});