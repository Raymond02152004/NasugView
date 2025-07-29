<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessOwnerAccount;


class BusinessController extends Controller
{
    public function index()
    {
        $businesses = BusinessOwnerAccount::all();
        return view('negosyo.business', compact('businesses'));
    }
}
