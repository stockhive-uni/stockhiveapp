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
                $insert[] = [
                    'order_id' => $last->id,
                    'item_id' => $item->id,
                    'ordered' => 0, //not sure if this needs changing for another feature
                     'price' => $item->price
                ];
                DB::table('order_item')->insert($insert);  //learnt how to only insert specific fields here, posted 8 years ago by StormShadow: https://laracasts.com/discuss/channels/eloquent/insert-to-data-base-on-the-fly-from-dynamic-content    
    
            }
        }
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
