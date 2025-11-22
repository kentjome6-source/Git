<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Pet;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->post_id = $post->id;
        
        $comment->save();
        
        $redirectRoute = route('social-media.show', $post);

        return redirect($redirectRoute)->with('success', 'Comment added successfully!');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Ensure the comment belongs to the authenticated user
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to comment.');
        }

        // Determine redirect route based on comment type
        if ($comment->post_id) {
            $redirectRoute = route('social-media.show', $comment->post_id);
        } else {
            $redirectRoute = route('pet.multipet.show', $comment->pet_id);
        }

        $comment->delete();

        return redirect($redirectRoute)->with('success', 'Comment deleted successfully!');
    }
}