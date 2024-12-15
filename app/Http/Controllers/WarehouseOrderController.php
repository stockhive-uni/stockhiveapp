<?php

namespace App\Http\Controllers;

use App\Models\item;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\JWT\Contract\Keys;
use Symfony\Component\VarExporter\Internal\Values;

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
            'store_id' => Auth::user()->store_id, //change this when store id is made properly
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
            
            return redirect()->route('stock-management');
        }
    }

    public function toOverview(Request $request) 
    {
        if ($request->id != null) {
            $ids = $request->id;
            $quantities = $request->quantity;
    
            $ids = array_map('intval', $ids);
            $quantities = array_map('intval', $quantities);
    
            $totals = [];
    
            // Creates a dictionary for duplicate items
            foreach ($ids as $index => $id) {
                if (isset($totals[$id])) {
                    $totals[$id] += $quantities[$index];
                } else {
                    $totals[$id] = $quantities[$index];
                }
            }
            ksort($totals);
            
            $ItemQty = array_values($totals);
    
            $totalPrice = $request->items;
            $totalItems = count($totals);
            $deliveryDate = now()->addDay(3);
    
            // Jacobs code
            $stock = new Collection();
            $collect =  Item::whereIn('id', array_keys($totals))->with('department')->get(); //originally had this very inefficient as it would fetch querys one by one, until i found whereIn which goes through the array of item ids:https://laravel.com/docs/11.x/eloquent-collections#method-intersect 
            $stock->push($collect);
    
            //overview stuff here like total cost, delivery date, total items
            return view('StockManager.overview',['items' => $stock, 'ItemQty' => $ItemQty, 'stock' => $stock, 'totalPrice' => $totalPrice, 'totalItems' => $totalItems, 'deliveryDate' => $deliveryDate]);
        }
        else {
            $items = DB::table('item')
                ->join('store_item', 'store_item.item_id', '=', 'item.id')
                ->where('store_item.store_id', '=', Auth::User()->store_id)
                ->select('item.id', 'item.name', 'store_item.price')
                ->get();

            return view('StockManager.order', compact('items'), ["error" => "Order unsuccessful, no items were selected"]);
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
