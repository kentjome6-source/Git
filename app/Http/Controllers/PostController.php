<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all posts with user information, ordered by newest first
        $posts = Post::with('user', 'comments', 'likes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.social-media.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.social-media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
        ]);

        $postData = [
            'user_id' => Auth::id(),
            'content' => $request->content,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $postData['image_path'] = $imagePath;
        }

        $post = Post::create($postData);

        return redirect()->route('social-media.index')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Log the post ID for debugging
        \Log::info('Loading post with ID: ' . $post->id);
        
        // Load the post with user, comments, and likes
        $post->load('user', 'comments.user', 'likes');
        
        return view('user.social-media.show', compact('post'));
    }

    /**
     * Show posts created by the authenticated user.
     */
    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with('comments', 'likes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('user.social-media.my-posts', compact('posts'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId)
    {
        // Log the post ID for debugging
        \Log::info('Attempting to delete post with ID: ' . $postId);
        
        // Find the post by ID
        $post = Post::findOrFail($postId);
        
        // Check if the authenticated user owns this post
        if ($post->user_id != Auth::id()) {
            \Log::warning('User ' . Auth::id() . ' attempted to delete post ' . $post->id . ' which belongs to user ' . $post->user_id);
            return redirect()->route('social-media.index')
                ->with('error', 'You are not authorized to delete this post.');
        }

        // Delete the post image if it exists
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();
        
        \Log::info('Post ' . $post->id . ' deleted successfully');

        return redirect()->route('social-media.my-posts')
            ->with('success', 'Post deleted successfully!');
    }

    /**
     * Like or unlike a post.
     */
    public function toggleLike(Post $post)
    {
        $user = Auth::user();
        
        // Check if the user has already liked this post
        $like = $post->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            // Unlike the post
            $like->delete();
            $liked = false;
        } else {
            // Like the post
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }
        
        // Return JSON response for AJAX
        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }
}