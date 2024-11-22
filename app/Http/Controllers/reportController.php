<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function index(Request $request) 
    {
        //here we use chart.js with the id of the item and orders to make a chart of order history
        $stock = Item::whereIn('id', $request->items)->with('department')->get();
        $allresults = array();
        foreach ($stock as $stockitem) {
            foreach ($stockitem as $item) {
                $id=$item->id;
                $orders = $item->orders;
                $query = DB::query('SELECT Transaction_Item.quantity, Transaction_Item.price, Transaction.date_time  FROM Transaction_Item, Transaction WHERE Transaction_Item.item_id = $id AND Transaction_Item.transaction_id = Transaction.id', [$id]);
                $quantity = $query->pluck('quantity');
                $price = $query->pluck('price');
                $date = $query->pluck('date_time');
                $month = date('m', strtotime($date)); // Get month from date
                $total = $quantity * $price;
                array_push($allresults, compact('total', 'month'));
            }
        }   
        return view('StockManager.report', ['allresults' => $allresults]);
    } 
}