<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Models\MessageRequest;
use App\Events\MessageRequestSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageRequestUpdated;
use App\Events\UnreadMessageCountUpdated;

class ChatMessageController extends Controller
{
    /**
     * Display the messages index page with contacts only
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        
        // Get accepted contacts (existing conversations)
        $contacts = $this->getAcceptedContacts($currentUser);
        
        // Get pending message requests with first message preview
        $pendingRequests = MessageRequest::where('recipient_id', $currentUser->id)
            ->where('status', 'pending')
            ->with(['sender', 'messages' => function($query) {
                $query->orderBy('created_at', 'asc')->limit(1);
            }])
            ->get();
        
        // Get message requests sent by current user
        $sentRequests = MessageRequest::where('sender_id', $currentUser->id)
            ->where('status', 'pending')
            ->with('recipient')
            ->get();
        
        // Initialize selectedUser as null (for the combined view)
        $selectedUser = null;
        
        // Use role-specific views
        if ($currentUser->role === 'vet') {
            return view('vet.messages.index', compact('contacts', 'pendingRequests', 'sentRequests', 'selectedUser'));
        } elseif ($currentUser->role === 'admin') {
            return view('admin.messages.index', compact('contacts', 'pendingRequests', 'sentRequests', 'selectedUser'));
        }
        
        return view('messages.index', compact('contacts', 'pendingRequests', 'sentRequests', 'selectedUser'));
    }

    /**
     * Load conversation via AJAX (for combined view)
     */
    public function loadConversation(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Check if conversation exists
        $messageRequest = MessageRequest::where(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })->first();
        
        // If no message request exists, cannot load conversation
        if (!$messageRequest) {
            return response()->json([
                'success' => false,
                'message' => 'No conversation exists with this user.'
            ], 403);
        }
        
        // Allow viewing if:
        // 1. Message request is accepted
        // 2. Current user is the sender (they can see their sent messages)
        // 3. Message request status is pending (both sender and recipient can view)
        if ($messageRequest->status === 'declined') {
            return response()->json([
                'success' => false,
                'message' => 'This message request has been declined.'
            ], 403);
        }
        
        // Get messages
        $messages = ChatMessage::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'messages' => $messages->map(function($message) use ($currentUser) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                    'is_sender' => $message->sender_id == $currentUser->id
                ];
            })
        ]);
    }

    /**
     * Show conversation page (separate page view)
     */
    public function conversation(User $user)
    {
        $currentUser = Auth::user();
        
        // Check if conversation exists
        $messageRequest = MessageRequest::where(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })->first();
        
        // If no message request exists, create a new one and redirect to messages index
        if (!$messageRequest) {
            // Create new message request
            $messageRequest = MessageRequest::create([
                'sender_id' => $currentUser->id,
                'recipient_id' => $user->id,
                'status' => 'pending',
                'accepted_at' => null
            ]);
            
            // Redirect to messages index where they can send the first message
            return redirect()->route('messages.index')
                ->with('info', 'Start a conversation with ' . $user->name);
        }
        
        // Allow viewing if:
        // 1. Message request is accepted
        // 2. Current user is the sender (they can see their sent messages)
        // 3. Message request status is pending (both sender and recipient can view)
        if ($messageRequest->status === 'declined') {
            return redirect()->route('messages.index')->with('error', 'This message request has been declined.');
        }
        
        // Get messages
        $messages = ChatMessage::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();
        
        $selectedUser = $user;
        
        if ($currentUser->role === 'vet') {
            return view('vet.messages.conversation', compact('selectedUser', 'messages'));
        }
        
        return view('messages.conversation', compact('selectedUser', 'messages'));
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $currentUser = Auth::user();
        $recipientId = $request->input('recipient_id');
        $messageText = $request->input('message');
        
        // Don't allow messaging oneself
        if ($currentUser->id == $recipientId) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot message yourself.'
            ], 403);
        }
        
        // Check if there's an existing message request
        $messageRequest = MessageRequest::where(function($query) use ($currentUser, $recipientId) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $recipientId);
        })->orWhere(function($query) use ($currentUser, $recipientId) {
            $query->where('sender_id', $recipientId)
                  ->where('recipient_id', $currentUser->id);
        })->first();
        
        // If no existing request, create one
        if (!$messageRequest) {
            $messageRequest = MessageRequest::create([
                'sender_id' => $currentUser->id,
                'recipient_id' => $recipientId,
                'status' => 'pending',
                'accepted_at' => null
            ]);
            
            // First message is always a request message type
            $messageType = 'request';
        } else {
            // Check if current user can message (must be accepted or sender of pending request)
            if ($messageRequest->status !== 'accepted' && 
                $messageRequest->sender_id !== $currentUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot message this user until they accept your request.'
                ], 403);
            }
            
            $messageType = $messageRequest->status === 'pending' ? 'request' : 'regular';
        }
        
        // Create the message
        $message = new ChatMessage();
        $message->sender_id = $currentUser->id;
        $message->recipient_id = $recipientId;
        $message->message = $messageText;
        $message->message_type = $messageType;
        $message->message_request_id = $messageRequest->id;
        $message->save();

        $message->load('sender');
        
        try {
            if ($messageType === 'request') {
                broadcast(new MessageRequestSent($messageRequest, $message))->toOthers();
            } else {
                broadcast(new MessageSent($message))->toOthers();
            }
            
            // Update unread count
            $unreadCount = ChatMessage::where('recipient_id', $recipientId)
                                      ->whereNull('read_at')
                                      ->count();
            
            broadcast(new UnreadMessageCountUpdated($recipientId, $unreadCount))->toOthers();
        } catch (\Exception $e) {
            // Log::error('Broadcast error: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'message_type' => $messageType,
            'request_status' => $messageRequest->status
        ]);
    }

    /**
     * Accept a message request
     */
    public function acceptRequest(Request $request, $requestId)
    {
        $currentUser = Auth::user();
        
        $messageRequest = MessageRequest::findOrFail($requestId);
        
        // Ensure the current user is the recipient
        if ($messageRequest->recipient_id !== $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        if ($messageRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Request already processed'
            ], 400);
        }
        
        $messageRequest->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
        
        // Update all request messages to regular type
        ChatMessage::where('message_request_id', $messageRequest->id)
            ->update(['message_type' => 'regular']);
        
        try {
            broadcast(new MessageRequestUpdated($messageRequest))->toOthers();
        } catch (\Exception $e) {
            // \Log::error('Broadcast error: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true,
            'message_request' => $messageRequest->load(['sender', 'recipient'])
        ]);
    }

    /**
     * Decline a message request
     */
    public function declineRequest(Request $request, $requestId)
    {
        $currentUser = Auth::user();
        
        $messageRequest = MessageRequest::findOrFail($requestId);
        
        // Ensure the current user is the recipient
        if ($messageRequest->recipient_id !== $currentUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        if ($messageRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Request already processed'
            ], 400);
        }
        
        $messageRequest->update([
            'status' => 'declined'
        ]);
        
        try {
            broadcast(new MessageRequestUpdated($messageRequest))->toOthers();
        } catch (\Exception $e) {
            // Log::error('Broadcast error: ' . $e->getMessage());
        }
        
        return response()->json([
            'success' => true
        ]);
    }

    public function getRequestsCount()
    {
        $currentUser = Auth::user();
    
        $count = MessageRequest::where('recipient_id', $currentUser->id)
            ->where('status', 'pending')
            ->count();
    
        return response()->json(['count' => $count]);
    }

    /**
     * Get accepted contacts with last message info
     */
    private function getAcceptedContacts($user)
    {
        // Get users with accepted message requests
        $acceptedRequests = MessageRequest::where('status', 'accepted')
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
            })
            ->get();
        
        $contactIds = [];
        foreach ($acceptedRequests as $request) {
            $contactIds[] = $request->sender_id == $user->id ? $request->recipient_id : $request->sender_id;
        }
        
        if (empty($contactIds)) {
            return collect();
        }
        
        $contacts = User::whereIn('id', $contactIds)->get();
        
        // Get last message for each contact
        foreach ($contacts as $contact) {
            $lastMessage = ChatMessage::where(function($query) use ($user, $contact) {
                $query->where('sender_id', $user->id)
                      ->where('recipient_id', $contact->id);
            })->orWhere(function($query) use ($user, $contact) {
                $query->where('sender_id', $contact->id)
                      ->where('recipient_id', $user->id);
            })->orderBy('created_at', 'desc')->first();
            
            if ($lastMessage) {
                $contact->last_message = $lastMessage->message;
                $contact->last_message_time = $lastMessage->created_at->diffForHumans();
            }
        }
        
        // Add unread counts
        return $this->addUnreadCounts($contacts, $user->id);
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, $userId)
    {
        $currentUser = Auth::user();
        
        // Update all messages from the sender to current user that are unread
        ChatMessage::where('sender_id', $userId)
                   ->where('recipient_id', $currentUser->id)
                   ->whereNull('read_at')
                   ->update(['read_at' => now()]);
        
        // Also broadcast the updated unread count
        $unreadCount = ChatMessage::where('recipient_id', $currentUser->id)
                                  ->whereNull('read_at')
                                  ->count();
        
        broadcast(new UnreadMessageCountUpdated($currentUser->id, $unreadCount))->toOthers();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Get unread message count for current user
     */
    public function getUnreadCount()
    {
        $currentUser = Auth::user();
        
        $unreadCount = ChatMessage::where('recipient_id', $currentUser->id)
                                  ->whereNull('read_at')
                                  ->count();
        
        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Poll for new messages
     */
    public function pollMessages(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $lastMessageId = $request->query('last_message_id', 0);
        
        // Check if there's a conversation
        $messageRequest = MessageRequest::where(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })->first();
        
        if (!$messageRequest || 
            ($messageRequest->status !== 'accepted' && $messageRequest->sender_id !== $currentUser->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid contact.'
            ], 403);
        }
        
        $messages = ChatMessage::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })
        ->where('id', '>', $lastMessageId)
        ->orderBy('created_at', 'asc')
        ->get();
        
        return response()->json([
            'success' => true,
            'messages' => $messages->map(function($message) use ($currentUser) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'recipient_id' => $message->recipient_id,
                    'message' => $message->message,
                    'created_at' => $message->created_at->timezone('Asia/Manila')->toISOString(),
                    'is_sender' => $message->sender_id == $currentUser->id
                ];
            })
        ]);
    }
    
    /**
     * Add unread counts to users collection
     */
    private function addUnreadCounts($users, $currentUserId)
    {
        $unreadCounts = ChatMessage::select('sender_id', DB::raw('count(*) as unread_count'))
                                  ->where('recipient_id', $currentUserId)
                                  ->whereNull('read_at')
                                  ->groupBy('sender_id')
                                  ->pluck('unread_count', 'sender_id');
        
        return $users->map(function ($user) use ($unreadCounts) {
            $user->unread_count = $unreadCounts[$user->id] ?? 0;
            return $user;
        });
    }
}