<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Create a shared channel name using the smaller and larger user IDs
        $minUserId = min($this->chatMessage->sender_id, $this->chatMessage->receiver_id);
        $maxUserId = max($this->chatMessage->sender_id, $this->chatMessage->receiver_id);
        
        // Broadcast to both the chat channel and individual user channels
        return [
            new PrivateChannel('chat.' . $minUserId . '.' . $maxUserId),
            new PrivateChannel('users.' . $this->chatMessage->sender_id),
            new PrivateChannel('users.' . $this->chatMessage->receiver_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->chatMessage->id,
            'sender_id' => $this->chatMessage->sender_id,
            'receiver_id' => $this->chatMessage->receiver_id,
            'message' => $this->chatMessage->message,
            'created_at' => $this->chatMessage->created_at->timezone('Asia/Manila')->toISOString(),
            'sender' => $this->chatMessage->sender,
        ];
    }
}