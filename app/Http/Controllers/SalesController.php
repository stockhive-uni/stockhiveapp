<?php

namespace App\Http\Controllers;

use App\Models\Item;

class SalesController extends Controller
{
    public function index()
    {
        return view('Sales.index');
    }
}
