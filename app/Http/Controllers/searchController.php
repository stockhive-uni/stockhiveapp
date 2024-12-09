<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class searchController extends Controller
{
    //
    public function search(Request $request) 
    {
        //search based off of query
        $searchAnswers = Item::where('name', 'like', "%". $request->search . "%")->paginate(10)->onEachSide(1);
        $searchQuery = $request->search;
        return (view('StockManager.search', ['items' => $searchAnswers, 'searchQuery' => $searchQuery]));
    } 
}
