<?php

namespace App\Http\Controllers;

use App\Models\Item;

class LogisticsController extends Controller
{
    public function index()
    {
        return view('Logistics.index');
    }
}
