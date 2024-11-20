<?php

namespace App\Http\Controllers;

use App\Models\item;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarehouseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //gather items being purchased
        $stock = new Collection();
        $collect =  Item::whereIn('id', $request->input('checkbox'))->with('department')->get(); 
        $stock->push($collect);

        //item qtys
        $itemQty = $request->input('ItemQty');
        
        //insert into Order table
        DB::table('order')->insert([
            'user_id' => Auth::id(),
            'store_id' => '1', //change this when store id is made properly
        ]
        );

        $last = DB::table('order')->latest('date_time')->first(); //gets the last record 
        //insert into OrderItem table
        foreach($stock as $product)     
        {
            foreach($product as $item) {
                //depending on which itemid, depends on the qty given
                $insert[] = [
                    'order_id' => $last->id,
                    'item_id' => $item->id,
                    'ordered' => $itemQty[$item->id], //uses the item id to get the item qty of the order
                     'price' => $item->price
                ];
                
            
            }
           DB::table('order_item')->insert($insert);  //learnt how to only insert specific fields here, posted 8 years ago by StormShadow: https://laracasts.com/discuss/channels/eloquent/insert-to-data-base-on-the-fly-from-dynamic-content    
        }
    }

    public function toOverview(Request $request) 
    {        
        //selected item ids from stock are then fetched again 
        $stock = new Collection();
        $collect =  Item::whereIn('id', $request->input('checkbox'))->with('department')->get(); //originally had this very inefficient as it would fetch querys one by one, until i found whereIn which goes through the array of item ids:https://laravel.com/docs/11.x/eloquent-collections#method-intersect 
        $stock->push($collect);

        //total cost and items
        $itemQty = $request->input('ItemQty');

        $totalPrice = 0;
        $totalItems = 0;
        foreach ($stock as $collection) {
            foreach ($collection as $item) {
                $Qty = $itemQty[$item->id];
                $totalPrice += ($Qty * $item->price);
                $totalItems += $Qty;
            }
        }

        //Quantities
        $ItemQty = $request->input('ItemQty');

        //delivery date
        $deliveryDate = now()->addDay(3);

        //overview stuff here like total cost, delivery date, total items
        return view('StockManager.overview',['items' => $stock, 'ItemQty' => $ItemQty, 'stock' => $stock, 'totalPrice' => $totalPrice, 'totalItems' => $totalItems, 'deliveryDate' => $deliveryDate]);
    }

    /**
     * Display the specified resource.
     */
    public function show(item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(item $item)
    {
        //
    }
}
