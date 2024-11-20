<?php

namespace App\Http\Controllers;

use App\Models\Item;

class InventoryController extends Controller
{
    public function index()
    {
        return view('Inventory.index');
    }
}
