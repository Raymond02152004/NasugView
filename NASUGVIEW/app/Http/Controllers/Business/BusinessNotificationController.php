<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BusinessNotificationController extends Controller
{
    public function index()
    {
        
        return view('business.notification');
    }
}
