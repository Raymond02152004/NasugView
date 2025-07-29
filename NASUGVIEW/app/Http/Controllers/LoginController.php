<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Signup;
use App\Models\Login;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $signup = Signup::where('email', $request->email)->first();

        if ($signup && Hash::check($request->password, $signup->password)) {
            $login = $signup->login;

            if (!$login) {
                return redirect('/login')->with('error', 'Login record not found.');
            }

            // ✅ SESSION SETUP
            Session::put('signup_id', $signup->signup_id);
            Session::put('login_id', $login->login_id);
            Session::put('role', $signup->role);
            Session::put('username', $signup->username);
            Session::put('profile_pic', $signup->profile_pic);

            // ✅ REDIRECT BASED ON ROLE
            switch ($signup->role) {
                case 'admin':
                    return redirect()->route('admin.account');
                case 'consumer':
                    return redirect()->route('consumer.home');
                case 'business owner':
                    return redirect()->route('business.home');
                case 'negosyo':
                    return redirect()->route('negosyo.dashboard');
                default:
                    return redirect('/login')->with('error', 'Invalid role.');
            }
        }

        return redirect('/login')->with('error', 'Invalid credentials.');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:signup,email',
            'password' => 'required|min:6',
        ]);

        try {
            $signup = Signup::create([
                'email'       => $request->email,
                'username'    => $request->username,
                'password'    => Hash::make($request->password),
                'role'        => 'consumer', // 🔒 force consumer only
                'profile_pic' => 'default.png',
            ]);

            $login = new Login();
            $login->signup_id = $signup->signup_id;
            $login->save();

            Session::put('signup_id', $signup->signup_id);
            Session::put('login_id', $login->login_id);
            Session::put('role', $signup->role);
            Session::put('username', $signup->username);
            Session::put('profile_pic', $signup->profile_pic);

            return redirect()->route('consumer.home')->with('registered', 'Account created successfully.');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Registration failed.');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
