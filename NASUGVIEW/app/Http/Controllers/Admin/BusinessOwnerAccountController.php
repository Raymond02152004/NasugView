<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signup;
use App\Models\BusinessOwnerAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BusinessOwnerAccountController extends Controller
{
    public function index()
    {
        $accounts = Signup::where('role', 'business owner')->get();
        return view('admin.businessaccounts', compact('accounts'));
    }

    public function sendPasswordReset($signup_id)
    {
        $account = Signup::findOrFail($signup_id);

        // ✔ Find business owner by business_name = username
        $businessOwner = BusinessOwnerAccount::where('business_name', $account->username)->first();

        if (!$businessOwner) {
            Log::error("No business owner found for username: " . $account->username);
            return redirect()->route('admin.businessaccounts')->with('error', 'Business owner not found.');
        }

        $personalEmail = $businessOwner->email;

        // ✔ Generate random 6-character password
        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

        // ✔ Update password
        $account->password = Hash::make($newPassword);
        $account->save();

        Log::info("Password updated for user: {$account->username}. New password: {$newPassword}");

        // ✔ Send the new password to the personal email
        try {
            Mail::raw("Hello {$account->username},\n\nYour new system password is: {$newPassword}\n\nPlease change it after logging in.", function ($message) use ($personalEmail) {
                $message->to($personalEmail)
                        ->subject('Your New System Password');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
            return redirect()->route('admin.businessaccounts')->with('error', 'Failed to send email.');
        }

        return redirect()->route('admin.businessaccounts')->with('success', 'New password sent to ' . $personalEmail);
    }
}
