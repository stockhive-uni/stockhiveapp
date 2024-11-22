<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //when loading into the stockManager view, items are displayed with their department
        $items = Item::with('department')
            ->paginate(5)
            ->onEachSide(1);
        return view('StockManager.index', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
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
        if ($request->has("Order")) {
            //selected item ids from stock are then fetched again 
            $stock = Item::whereIn('id', $request->items)->with('department')->paginate(3)->onEachSide(1); //originally had this very inefficient as it would fetch querys one by one, until i found whereIn which goes through the array of item ids:https://laravel.com/docs/11.x/eloquent-collections#method-intersect 

            return (view('StockManager.order', ['items' => $stock, 'allItems' => $request->items]));
        }
        else {
            $stock = Item::whereIn('id', $request->items)->with('department')->get();
            $allresults = array();
            foreach ($stock as $item) {
                $id=$item->id;
                $orders = $item->orders;
                $query = DB::query('SELECT Transaction_Item.quantity, Transaction_Item.price, Transaction.date_time  FROM Transaction_Item, Transaction WHERE Transaction_Item.item_id = ? AND Transaction_Item.transaction_id = Transaction.id', [$id]);
                if (!empty($query)) {
                    /* Not fully functional
                    $quantity = collect($query)->pluck('quantity');
                    $price = collect($query)->pluck('price');
                    $date = collect($query)->pluck('date_time');
                    $month = date('m', strtotime($date)); // Get month from date
                    $total = $quantity * $price;
                    array_push($allresults, compact('total', 'month'));
                    */
                }
            } 
            return view('StockManager.report', ['allresults' => $allresults]);
        }
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
