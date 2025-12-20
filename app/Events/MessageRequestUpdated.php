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

class MessageRequestUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageRequest;

    public function __construct(MessageRequest $messageRequest)
    {
        $this->messageRequest = $messageRequest;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('messages.' . $this->messageRequest->sender_id),
            new PrivateChannel('messages.' . $this->messageRequest->recipient_id)
        ];
    }

    public function broadcastAs()
    {
        return 'message-request-updated';
    }

    public function broadcastWith()
    {
        return [
            'message_request' => [
                'id' => $this->messageRequest->id,
                'sender_id' => $this->messageRequest->sender_id,
                'recipient_id' => $this->messageRequest->recipient_id,
                'status' => $this->messageRequest->status,
                'accepted_at' => $this->messageRequest->accepted_at,
                'sender' => [
                    'id' => $this->messageRequest->sender->id,
                    'name' => $this->messageRequest->sender->name,
                ],
                'recipient' => [
                    'id' => $this->messageRequest->recipient->id,
                    'name' => $this->messageRequest->recipient->name,
                ]
            ]
        ];
    }
}