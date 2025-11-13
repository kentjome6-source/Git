<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChatMessage;
use App\Models\User;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to create test messages between them
        $users = User::limit(2)->get();
        
        if ($users->count() >= 2) {
            // Create some test messages
            ChatMessage::create([
                'sender_id' => $users[0]->id,
                'receiver_id' => $users[1]->id,
                'message' => 'Hello, this is a test message!',
            ]);
            
            ChatMessage::create([
                'sender_id' => $users[1]->id,
                'receiver_id' => $users[0]->id,
                'message' => 'Hi there! Thanks for your message.',
            ]);
            
            ChatMessage::create([
                'sender_id' => $users[0]->id,
                'receiver_id' => $users[1]->id,
                'message' => 'How are you doing today?',
            ]);
        }
    }
}