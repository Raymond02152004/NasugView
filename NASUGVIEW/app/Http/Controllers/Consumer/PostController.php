<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        if (!Session::has('signup_id')) {
            return redirect()->back()->with('error', 'Session expired. Please log in again.');
        }

        $request->validate([
            'content' => 'nullable|string',
            'media.*' => 'nullable|mimes:jpeg,png,jpg,mp4,mov|max:10240',
        ]);

        $signupId = Session::get('signup_id');
        $mediaPaths = [];

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $mediaPaths[] = $path;
                
            }
        }

        try {
            Post::create([
                'signup_id'   => $signupId,
                'content'     => $request->input('content'),
                'media_paths' => json_encode($mediaPaths),
                'status' => 'pending',

            ]);

            return redirect()->back()->with('success', 'Post submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Post store failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Post failed to save.');
        }
    }

}
