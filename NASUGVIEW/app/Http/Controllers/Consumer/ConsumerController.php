<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Signup; // Adjust this if you're using a different model

class ConsumerController extends Controller
{
    public function home()
    {
        // Get user ID from session
        $loginId = Session::get('login_id');

        // Find the Signup record using the login relationship
        $signup = Signup::whereHas('login', function ($q) use ($loginId) {
            $q->where('login_id', $loginId);
        })->first();

        // Safety check
        if ($signup) {
            Session::put('username', $signup->username);
            Session::put('profile_pic', $signup->profile_pic);
        }

        return view('consumer.home');
    }
}