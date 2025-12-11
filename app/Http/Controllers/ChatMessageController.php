<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Events\UnreadMessageCountUpdated;
use App\Jobs\SendMessageJob;

class ChatMessageController extends Controller
{
    /**
     * Display the messages index page with contacts and messages
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        
        // Get contacts (other users excluding current user)
        // For veterinarians, only show users they have appointments with
        // For pet parents, show all other pet parents
        $users = $this->getContacts($currentUser);
        
        // Add unread counts to users
        $users = $this->addUnreadCounts($users, $currentUser->id);
        
        // Get selected user (first contact by default)
        $selectedUserId = $request->query('user');
        if (!$selectedUserId && !$users->isEmpty()) {
            $selectedUserId = $users->first()->id;
        }
        
        // Get messages with selected user
        $messages = collect();
        if ($selectedUserId) {
            $messages = ChatMessage::where(function ($query) use ($currentUser, $selectedUserId) {
                $query->where('sender_id', $currentUser->id)
                      ->where('recipient_id', $selectedUserId);
            })->orWhere(function ($query) use ($currentUser, $selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('recipient_id', $currentUser->id);
            })->orderBy('created_at', 'asc')->get();
        }
        
        // Use veterinarian-specific view if user is a vet
        if ($currentUser->role === 'vet') {
            return view('vet.messages.index', compact('users', 'selectedUserId', 'messages'));
        }
        
        return view('messages.index', compact('users', 'selectedUserId', 'messages'));
    }
    
    /**
     * Show messages with a specific user
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();
        
        // Get contacts
        $users = $this->getContacts($currentUser);
        
        // Add unread counts to users
        $users = $this->addUnreadCounts($users, $currentUser->id);
        
        // Get messages with selected user
        $messages = ChatMessage::where(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $currentUser->id)
                  ->where('recipient_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('sender_id', $user->id)
                  ->where('recipient_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();
        
        // Set selected user ID for the view
        $selectedUserId = $user->id;
        
        // Use veterinarian-specific view if user is a vet
        if ($currentUser->role === 'vet') {
            return view('vet.messages.index', compact('users', 'selectedUserId', 'messages'));
        }
        
        return view('messages.index', compact('users', 'selectedUserId', 'messages'));
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
        
        // Validate that the recipient is a valid contact
        $isValidContact = $this->isValidContact($currentUser, $recipientId);
        if (!$isValidContact) {
            return response()->json([
                'success' => false,
                'message' => 'You can only message users you have appointments with.'
            ], 403);
        }

        // Create the message
        $message = new ChatMessage();
        $message->sender_id = $currentUser->id;
        $message->recipient_id = $recipientId;
        $message->message = $messageText;
        $message->save();

        try {
            broadcast(new MessageSent($message))->toOthers();

            $unreadCount = ChatMessage::where('recipient_id', $message->recipient_id)
                                      ->whereNull('read_at')
                                      ->count();

            broadcast(new UnreadMessageCountUpdated($message->recipient_id, $unreadCount))->toOthers();
        } catch (\Exception $e) {
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
            'unread_count' => $unreadCount ?? 0
        ]);
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Update all messages from this user to current user that are unread
        ChatMessage::where('sender_id', $user->id)
                   ->where('recipient_id', $currentUser->id)
                   ->whereNull('read_at')
                   ->update(['read_at' => now()]);
        
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
        
        // Validate that the user is a valid contact
        $isValidContact = $this->isValidContact($currentUser, $user->id);
        if (!$isValidContact) {
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
     * Get contacts based on user role
     */
    private function getContacts($user)
    {
        if ($user->role === 'vet') {
            // For veterinarians, only show pet parents they have appointments with
            // AND ensure they are legitimate pet parent accounts
            // Only show pet parents with accepted appointments
            return User::where('role', 'user')
                      ->whereHas('appointments', function ($query) use ($user) {
                          $query->where('vet_id', $user->id)
                                ->where('status', 'accepted');
                      })
                      ->get();
        } else {
            // For pet parents, show:
            // 1. Other pet parents
            // 2. Veterinarians they have appointments with (only accepted appointments)
            $petParents = User::where('role', 'user')
                           ->where('id', '!=', $user->id)
                           ->get();
            
            $veterinarians = User::where('role', 'vet')
                              ->whereHas('vetAppointments', function ($query) use ($user) {
                                  $query->where('user_id', $user->id)
                                        ->where('status', 'accepted');
                              })
                              ->get();
            
            // Merge the collections
            return $petParents->merge($veterinarians);
        }
    }
    
    /**
     * Add unread counts to users collection
     */
    private function addUnreadCounts($users, $currentUserId)
    {
        // Note: This query needs to be updated to match the actual database structure
        // The error was occurring because this query was using 'receiver_id' and 'is_read'
        // but the database actually has 'recipient_id' and 'read_at'
        // but the database actually has 'recipient_id' and 'read_at'
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
    
    /**
     * Check if a recipient is a valid contact for the current user
     */
    private function isValidContact($currentUser, $recipientId)
    {
        // Don't allow messaging oneself
        if ($currentUser->id == $recipientId) {
            return false;
        }
        
        // Get the recipient user
        $recipient = User::find($recipientId);
        if (!$recipient) {
            return false;
        }
        
        if ($currentUser->role === 'vet') {
            // Veterinarians can only message pet parents they have appointments with
            // AND ensure the recipient is a legitimate pet parent account
            // Only allow messaging pet parents with accepted appointments
            if ($recipient->role !== 'user') {
                return false;
            }
            
            return $recipient->appointments()
                            ->where('vet_id', $currentUser->id)
                            ->where('status', 'accepted')
                            ->exists();
        } else {
            // Pet parents can message:
            // 1. Other pet parents (no appointment required)
            // 2. Veterinarians they have appointments with (only accepted appointments)
            if ($recipient->role === 'user') {
                // Pet parent messaging another pet parent - allowed
                return true;
            } elseif ($recipient->role === 'vet') {
                // Pet parent messaging a veterinarian - only if they have an accepted appointment
                return $recipient->vetAppointments()
                                ->where('user_id', $currentUser->id)
                                ->where('status', 'accepted')
                                ->exists();
            } else {
                // Don't allow messaging admins or other roles
                return false;
            }
        }
    }
}