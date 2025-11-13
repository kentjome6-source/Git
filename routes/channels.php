<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Appointment;
use App\Models\User;

Broadcast::channel('users.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('chat.{userId1}.{userId2}', function ($user, $userId1, $userId2) {
    // Users can access the chat channel if they are either of the two users in the conversation
    return (int) $user->id === (int) $userId1 || (int) $user->id === (int) $userId2;
});
