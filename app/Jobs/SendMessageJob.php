<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Events\MessageSent;
use App\Events\UnreadMessageCountUpdated;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Log;

class SendMessageJob implements ShouldQueue
{
    use Queueable;

    protected $messageId;

    /**
     * Create a new job instance.
     */
    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Retrieve the message
        $message = ChatMessage::find($this->messageId);
        
        if ($message) {
            // Broadcast the message
            broadcast(new MessageSent($message))->toOthers();
            
            // Update unread count for receiver
            $unreadCount = ChatMessage::where('recipient_id', $message->recipient_id)
                                      ->whereNull('read_at')
                                      ->count();
            
            broadcast(new UnreadMessageCountUpdated($message->recipient_id, $unreadCount))->toOthers();
            
            Log::info('Message sent via queue: ' . $message->id);
        } else {
            Log::warning('Message not found for queue job: ' . $this->messageId);
        }
    }
}