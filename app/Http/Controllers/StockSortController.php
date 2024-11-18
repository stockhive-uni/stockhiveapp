<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;  

class StockSortController extends Controller {
    public function sort(Request $request) {
        $sort = $request->input('sort', 'id'); // Default to ID
        $items = Item::with('department') // Stackoverflow answer for sorting using Laravel https://stackoverflow.com/a/60182632
                    ->orderBy($sort) 
                    ->simplePaginate(5); // Pagination for the items
        return view('StockManager.index', ['items' => $items]);
    }
}