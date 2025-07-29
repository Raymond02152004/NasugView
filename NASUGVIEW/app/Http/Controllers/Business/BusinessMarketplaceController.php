<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BusinessPost;

class BusinessMarketplaceController extends Controller
{
    /**
     * Show the marketplace posting form or redirect if already posted.
     */
    public function index()
    {
        $signupId = session('signup_id');

        // Check if this user already has a post
        $existingPost = BusinessPost::where('signup_id', $signupId)->first();

        if ($existingPost) {
            return redirect()->route('business.viewpost', ['id' => $existingPost->business_id]);
        }

        return view('business.marketplace');
    }

    /**
     * Store the posted business details.
     */
    public function store(Request $request)
    {
        $signupId = session('signup_id');

        // Prevent double posting
        $existingPost = BusinessPost::where('signup_id', $signupId)->first();
        if ($existingPost) {
            return redirect()->route('business.viewpost', ['id' => $existingPost->business_id])
                             ->with('success', 'You have already posted your business.');
        }

        $request->validate([
            'business_name'   => 'required|string|max:255',
            'description'     => 'required|string',
            'contact_info'    => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'image'           => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'business_name',
            'description',
            'contact_info',
            'address',
            'latitude',
            'longitude',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('business_images', 'public');
        }

        $data['signup_id'] = $signupId;

        // Create new post
        $businessPost = BusinessPost::create($data);

        return redirect()->route('business.viewpost', ['id' => $businessPost->business_id])
                         ->with('success', 'Business posted successfully!');
    }

    /**
     * Display the posted business details.
     */
    public function viewPost($id)
    {
        $post = BusinessPost::findOrFail($id);
        return view('business.businessview-post', compact('post'));
    }

    /**
     * Show the edit form for the post.
     */
    public function edit($id)
    {
        $post = BusinessPost::findOrFail($id);

        // Optional: Verify that the logged-in user owns this post
        if ($post->signup_id !== session('signup_id')) {
            return redirect()->route('business.marketplace')->with('error', 'Unauthorized access.');
        }

        return view('business.editpost', compact('post'));
    }
}
