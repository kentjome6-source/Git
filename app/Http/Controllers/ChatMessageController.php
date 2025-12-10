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
        
        // Create the message
        $message = new ChatMessage();
        $message->sender_id = $currentUser->id;
        $message->recipient_id = $recipientId;
        $message->message = $messageText;
        $message->save();
        
        // Dispatch the job to send the message
        SendMessageJob::dispatch($message->id);
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id'
        ]);
        
        $currentUser = Auth::user();
        $contactId = $request->input('contact_id');
        
        // Mark messages as read
        ChatMessage::where('sender_id', $contactId)
                   ->where('recipient_id', $currentUser->id)
                   ->whereNull('read_at')
                   ->update(['read_at' => now()]);
        
        // Get updated unread count
        $unreadCount = ChatMessage::where('recipient_id', $currentUser->id)
                                  ->whereNull('read_at')
                                  ->count();
        
        // Broadcast updated unread count
        broadcast(new UnreadMessageCountUpdated($currentUser->id, $unreadCount))->toOthers();
        
        return response()->json([
            'success' => true
        ]);
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
     * Get unread message count for a specific contact
     */
    public function getContactUnreadCount(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id'
        ]);
        
        $contactId = $request->input('contact_id');
        $currentUser = Auth::user();
        
        $unreadCount = ChatMessage::where('sender_id', $contactId)
                                  ->where('recipient_id', $currentUser->id)
                                  ->whereNull('read_at')
                                  ->count();
        
        return response()->json([
            'unread_count' => $unreadCount
        ]);
    }
    
    /**
     * Get contacts based on user role
     */
    private function getContacts($user)
    {
        if ($user->role === 'vet') {
            // For veterinarians, get users they have appointments with
            return User::where('role', 'user')
                      ->whereHas('appointments', function ($query) use ($user) {
                          $query->where('vet_id', $user->id);
                      })
                      ->get();
        } else {
            // For pet parents, get all other pet parents AND veterinarians they have appointments with
            $petParents = User::where('role', 'user')
                           ->where('id', '!=', $user->id)
                           ->get();
            
            $veterinarians = User::where('role', 'vet')
                              ->whereHas('vetAppointments', function ($query) use ($user) {
                                  $query->where('user_id', $user->id);
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