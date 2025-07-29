<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index()
    {
        return view('consumer.marketplace'); 
    }
}
