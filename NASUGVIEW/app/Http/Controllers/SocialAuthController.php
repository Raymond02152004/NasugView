<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Signup;
use App\Models\Login;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $userSocial = Socialite::driver($provider)->user();

        $email = $userSocial->getEmail();
        $name = $userSocial->getName();
        $avatar = $userSocial->getAvatar();

        // Check if the user already exists
        $signup = Signup::where('email', $email)->first();

        if (!$signup) {
            $signup = Signup::create([
                'username'    => $name,
                'email'       => $email,
                'password'    => Hash::make('defaultpassword'),
                'role'        => 'consumer',
                'profile_pic' => $avatar,
            ]);

            Login::create([
                'signup_id' => $signup->signup_id,
            ]);
        }

        $login = $signup->login ?? Login::where('signup_id', $signup->signup_id)->first();

        // ✅ Store session data
        Session::put('signup_id', $signup->signup_id);
        Session::put('login_id', $login->login_id ?? null);
        Session::put('username', $signup->username);
        Session::put('role', $signup->role);
        Session::put('profile_pic', $signup->profile_pic);

        return redirect()->route('consumer.home');
    }
}
