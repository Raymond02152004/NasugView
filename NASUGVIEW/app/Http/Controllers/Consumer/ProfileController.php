<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Signup;

class ProfileController extends Controller
{
    // Show the logged-in user's profile
    public function show()
{
    $signupId = session('signup_id');

    if (!$signupId) {
        return redirect('/login')->with('error', 'You must be logged in to view your profile.');
    }

    $user = Signup::with(['posts' => function ($query) {
        $query->latest();
    }])->findOrFail($signupId);

    return view('consumer.profile', compact('user')); // ✅ FIXED
}


    // Show someone else's profile (from clicking post author)
    public function showProfile($id)
{
    $user = Signup::with(['posts' => function ($query) {
        $query->latest();
    }])->where('signup_id', $id)->firstOrFail();

    return view('consumer.profile_view', compact('user'));
}
}
