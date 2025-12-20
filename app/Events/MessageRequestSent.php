<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MessageRequest;
use App\Models\ChatMessage;

class MessageRequestSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageRequest;
    public $message;

    public function __construct(MessageRequest $messageRequest, ChatMessage $message)
    {
        $this->messageRequest = $messageRequest;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('messages.' . $this->messageRequest->recipient_id);
    }

    public function broadcastAs()
    {
        return 'message-request-sent';
    }

    public function broadcastWith()
    {
        return [
            'message_request' => [
                'id' => $this->messageRequest->id,
                'sender_id' => $this->messageRequest->sender_id,
                'recipient_id' => $this->messageRequest->recipient_id,
                'status' => $this->messageRequest->status,
            ],
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'recipient_id' => $this->message->recipient_id,
                'message' => $this->message->message,
                'message_type' => $this->message->message_type,
                'created_at' => $this->message->created_at,
                'sender' => [
                    'id' => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                    'profile_picture_url' => $this->message->sender->profile_picture_url,
                ]
            ]
        ];
    }
}