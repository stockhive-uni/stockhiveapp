<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\warehouseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\warehouseOrderedItems;

class dashboardController extends Controller
{
    //

    public function index() {

        //fetches the order history related to the store, with the user who made the order, and the items contained in the order
        $orderHistory = warehouseOrder::with(['users', 'order_item'])->where('store_id', Auth::User()->store_id)->limit(5)->get();
        //calculate the total number of items ordered in that order
        return (view('dashboard', ['orderHistory' => $orderHistory]));
    } 

    public function ShowOrderHistory(Request $request) {
        $orders = warehouseOrder::with(['users', 'order_item'])
        ->where('store_id', '=', Auth::User()->store_id)
        ->where('id', '=', $request->order)
        ->get();

        $orderHistoryItems = DB::table('order_item')
        ->join('item', 'item.id', '=', 'order_item.item_id')
        ->join('order', 'order.id', '=', 'order_item.order_id')
        ->where('order.id', '=', $request->order)
        ->get();

        return (view('order-history', ['orderHistoryItems' => $orderHistoryItems, 'orders' => $orders]));
    }
}
