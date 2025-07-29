<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Signup;

class BusinessProfileController extends Controller
{
    public function show()
    {
        $signupId = session('signup_id');

        if (!$signupId) {
            return redirect('/login')->with('error', 'You must be logged in to view your profile.');
        }

        $user = Signup::with(['posts' => function ($query) {
            $query->latest();
        }])->findOrFail($signupId);

        return view('business.businessprofile', compact('user'));
    }

    public function showProfile($id)
    {
        $user = Signup::with(['posts' => function ($query) {
            $query->latest();
        }])->where('signup_id', $id)->firstOrFail();

        return view('business.businessprofile_view', compact('user'));
    }
}
