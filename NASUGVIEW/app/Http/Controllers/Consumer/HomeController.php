<?php

namespace App\Http\Controllers\Consumer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ Only fetch posts with status = 'approved'
        $posts = Post::with('signup')
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('consumer.home', compact('posts'));
    }
}
