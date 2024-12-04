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
            $stock = null;
            if ($request->has("Report")) {
                $stock = Item::whereIn('id', $request->items)->with('department')->get();
            }
            else {
                $stock = Item::where('id', $request->item)->with('department')->get();
            }
            $allresults = array();
            // Loop through all items.
            foreach ($stock as $item) {
                $id=$item->id;
                $query = DB::table('Transaction_Item') // Use laravel's query builder. https://www.google.com/search?client=firefox-b-d&q=laravel+query+builder
                    ->join('Transaction', 'Transaction_Item.transaction_id', '=', 'Transaction.id')
                    ->where('Transaction_Item.item_id', $id)
                    ->select('Transaction_Item.quantity', 'Transaction_Item.price', 'Transaction.date_time')
                    ->get();

                if ($query->isNotEmpty()) {
                    $data = [];
                    // Iterate through transactions.
                    foreach ($query as $transaction) {
                        $quantity = $transaction->quantity;
                        $price = $transaction->price;
                        $date = $transaction->date_time;
                        // Get the month from date
                        $month = date('m', strtotime($date)); // Getting date using strtotime: https://www.php.net/manual/en/function.strtotime.php
                        // Store the data in an array, group by month.
                        if (!isset($data[$month])) {
                            $data[$month] = [
                                'total' => 0, 
                                'month' => $month,
                            ];
                        }
                        // Set the total for the month.
                        $data[$month]['total'] += $quantity * $price;
                    }
                     // Add the item's monthly data
                    $allresults[] = [
                        'item_name' => $item->name,  // Item name
                        'data' => $data, // Item information per month.
                    ];
                }
                else {
                    $data = [];
                    $data[0] = [
                        'total' => 0,
                        'month' => 0,
                    ];
                    $allresults[] = [
                        'item_name' => $item->name,
                        'data' => $data,
                    ];
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
