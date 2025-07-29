<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class BusinessHomeController extends Controller
{
    public function index()
    {
        // ✅ Fetch only approved posts with the related user (signup), latest first
        $posts = Post::with('signup')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('business.home', compact('posts'));
    }
}
