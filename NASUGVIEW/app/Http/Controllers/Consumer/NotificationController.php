<?php

namespace App\Http\Controllers\Consumer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        return view('consumer.notification'); 
    }
}
