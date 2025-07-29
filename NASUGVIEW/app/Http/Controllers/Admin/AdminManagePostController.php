<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class AdminManagePostController extends Controller
{
   public function index()
{
    $pendingPosts = \App\Models\Post::with('signup')
                    ->where('status', 'pending')
                    ->latest()
                    ->get();

    return view('admin.manage-posts', compact('pendingPosts'));
}

    public function approve($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'approved';
        $post->save();

        return redirect()->back()->with('success', 'Post approved.');
    }

    public function reject($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'rejected';
        $post->save();

        return redirect()->back()->with('success', 'Post rejected.');
    }


    public function view($id)
    {
        $post = Post::with('signup')->findOrFail($id);
        return view('admin.view-post', compact('post'));
    }


}
