<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Events\UnreadMessageCountUpdated;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Display the chat interface for regular users.
     * Show all legitimate users except the current user.
     */
    public function index(Request $request)
    {
        // Get all legitimate users except the current user and admins
        // Filter out test/sample accounts using the legitimate scope
        $users = User::where('id', '!=', Auth::id())
                    ->where('role', '!=', 'admin')
                    ->legitimate()
                    ->get();
        
        // Add unread message counts to each user
        foreach ($users as $user) {
            $user->unread_count = ChatMessage::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
        
        // If there's a selected user, get messages between current user and selected user
        $selectedUserId = $request->query('user');
        $messages = collect();
        
        if ($selectedUserId) {
            $messages = ChatMessage::where(function($query) use ($selectedUserId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $selectedUserId);
            })->orWhere(function($query) use ($selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
            
            // Mark messages from the selected user as read
            ChatMessage::where('sender_id', $selectedUserId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        
        return view('user.messages.index', compact('users', 'messages', 'selectedUserId'));
    }

    /**
     * Display the vet chat interface.
     * Only show regular users (not vets or admins) in the contact list.
     */
    public function vetIndex(Request $request)
    {
        // Get only regular users (not vets or admins) except the current vet
        // Filter out test/sample accounts using the legitimate scope
        $users = User::where('role', 'user')
                    ->where('id', '!=', Auth::id())
                    ->where('role', '!=', 'admin')
                    ->legitimate()
                    ->get();
        
        // Add unread message counts to each user
        foreach ($users as $user) {
            $user->unread_count = ChatMessage::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
        
        // If there's a selected user, get messages between current vet and selected user
        $selectedUserId = $request->query('user');
        $messages = collect();
        
        if ($selectedUserId) {
            $messages = ChatMessage::where(function($query) use ($selectedUserId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $selectedUserId);
            })->orWhere(function($query) use ($selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
            
            // Mark messages from the selected user as read
            ChatMessage::where('sender_id', $selectedUserId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        
        return view('vet.messages.index', compact('users', 'messages', 'selectedUserId'));
    }

    /**
     * Send a new message.
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        // Verify that the receiver is a valid contact
        $receiver = User::find($request->receiver_id);
        $sender = Auth::user();
        
        // Regular users can message other users or vets
        if ($sender->role === 'user' && !in_array($receiver->role, ['user', 'vet'])) {
            return response()->json(['success' => false, 'message' => 'Users can only message other users or veterinary staff'], 403);
        }
        
        // Vets can only message regular users
        if ($sender->role === 'vet' && $receiver->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'Veterinary staff can only message users'], 403);
        }
        
        // Additional check: Ensure receiver is a legitimate user
        if (!$receiver->legitimate()->exists()) {
            return response()->json(['success' => false, 'message' => 'Cannot message this user'], 403);
        }

        $chatMessage = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Load the sender relationship for broadcasting
        $chatMessage->load('sender');

        // First, broadcast the message to the chat channel
        broadcast(new MessageSent($chatMessage))->toOthers();

        // Small delay to ensure database consistency
        usleep(100000); // 0.1 second delay

        // Then, update unread count for the receiver and broadcast it immediately
        $receiverUnreadCount = ChatMessage::where('receiver_id', $request->receiver_id)
            ->where('is_read', false)
            ->count();
        broadcast(new UnreadMessageCountUpdated($request->receiver_id, $receiverUnreadCount))->toOthers();
        
        // Finally, update unread count for the sender and broadcast it immediately
        $senderUnreadCount = ChatMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
        broadcast(new UnreadMessageCountUpdated(Auth::id(), $senderUnreadCount))->toOthers();

        return response()->json(['success' => true, 'message' => $chatMessage]);
    }

    /**
     * Fetch messages between two users.
     */
    public function fetchMessages(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Verify that the user is a valid contact
        $user = User::find($request->user_id);
        $sender = Auth::user();
        
        // Regular users can fetch messages with other users or vets
        if ($sender->role === 'user' && !in_array($user->role, ['user', 'vet'])) {
            return response()->json(['messages' => []]);
        }
        
        // Vets can only fetch messages with regular users
        if ($sender->role === 'vet' && $user->role !== 'user') {
            return response()->json(['messages' => []]);
        }
        
        // Additional check: Ensure user is a legitimate user
        if (!$user->legitimate()->exists()) {
            return response()->json(['messages' => []]);
        }

        $messages = ChatMessage::where(function($query) use ($request) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $request->user_id);
        })->orWhere(function($query) use ($request) {
            $query->where('sender_id', $request->user_id)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Mark messages from the selected user as read
        ChatMessage::where('sender_id', $request->user_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }
    
    /**
     * Get unread message count for the current user
     */
    public function getUnreadCount(): JsonResponse
    {
        $currentUser = Auth::user();
        
        if ($currentUser->role === 'vet') {
            // For vets, only count messages from regular users (not admins)
            $validSenderIds = User::where('role', 'user')->legitimate()->pluck('id');
            $unreadCount = ChatMessage::where('receiver_id', Auth::id())
                ->whereIn('sender_id', $validSenderIds)
                ->where('is_read', false)
                ->count();
        } elseif ($currentUser->role === 'user') {
            // For users, count messages from other users and vets
            $validSenderIds = User::whereIn('role', ['user', 'vet'])->legitimate()->pluck('id');
            $unreadCount = ChatMessage::where('receiver_id', Auth::id())
                ->whereIn('sender_id', $validSenderIds)
                ->where('is_read', false)
                ->count();
        } else {
            // For admins, count all unread messages (though they shouldn't have any in practice)
            $unreadCount = ChatMessage::where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
            
        return response()->json(['unread_count' => $unreadCount]);
    }
    
    /**
     * Get unread message count for a specific contact
     */
    public function getContactUnreadCount(Request $request): JsonResponse
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id',
        ]);
        
        // Verify that the contact is a legitimate user
        $contact = User::find($request->contact_id);
        if (!$contact->legitimate()->exists()) {
            return response()->json(['unread_count' => 0]);
        }
        
        $unreadCount = ChatMessage::where('sender_id', $request->contact_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
            
        return response()->json(['unread_count' => $unreadCount]);
    }
    
    /**
     * Mark all messages from a contact as read
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id',
        ]);
        
        // Verify that the contact is a legitimate user
        $contact = User::find($request->contact_id);
        if (!$contact->legitimate()->exists()) {
            return response()->json(['success' => false]);
        }
        
        ChatMessage::where('sender_id', $request->contact_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        // Broadcast updated unread count to all users (including the current user)
        $unreadCount = ChatMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
        broadcast(new UnreadMessageCountUpdated(Auth::id(), $unreadCount))->toOthers();
            
        return response()->json(['success' => true]);
    }
}