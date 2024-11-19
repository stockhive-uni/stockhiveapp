<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //when loading into the stockManager view, items are displayed with their department
        $items = Item::with('department')->get();
        return (view('StockManager.index', ['items' => $items]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('StockManager.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function chosenItems(Request $request) 
    {
        //selected item ids from stock are then fetched again 
        $stock = new Collection();
            $collect =  Item::whereIn('id', $request->items)->with('department')->get(); //originally had this very inefficient as it would fetch querys one by one, until i found whereIn which goes through the array of item ids:https://laravel.com/docs/11.x/eloquent-collections#method-intersect 
            $stock->push($collect);

        return (view('StockManager.order', ['items' => $stock]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
