<?php

namespace App\Http\Controllers;

use App\Models\Item;

class AdminController extends Controller
{
    public function index()
    {
        return view('Admin.index');
    }
}
