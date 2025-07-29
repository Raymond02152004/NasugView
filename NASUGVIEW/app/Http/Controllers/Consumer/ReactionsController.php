<?php

namespace App\Http\Controllers;

use App\Models\Reaction;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionsController extends Controller
{
    public function react(Request $request, $postId)
    {
        // Check if the user already reacted (liked) this post
        $existingReaction = Reaction::where('signup_id', Auth::id())  // Using signup_id instead of user_id
                                    ->where('post_id', $postId)
                                    ->first();

        if ($existingReaction) {
            // If the user has already liked the post, we'll remove the like (unlike)
            $existingReaction->delete();
        } else {
            // Create new reaction (like)
            Reaction::create([
                'signup_id' => Auth::id(),  // Use signup_id here
                'post_id' => $postId,
                'reaction_type' => 'like', // Only 'like' reactions
            ]);
        }

        return back(); // Redirect back to the page
    }
}
