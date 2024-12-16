<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\warehouseOrder;
use App\Models\transaction_item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\warehouseOrderedItems;

class dashboardController extends Controller
{
    //

    public function index() {

        //fetches the order history related to the store, with the user who made the order, and the items contained in the order
        $orderHistory = warehouseOrder::with(['users', 'order_item'])->where('store_id', Auth::User()->store_id)->limit(5)->get();

        $numberOfOrders = warehouseOrder::where('store_id', '=', Auth::user()->store_id)->count();

        $numberOfSales = Transaction::with('transaction_item')->where('store_id', '=', Auth::user()->store_id)->count();

        $ItemsSoldThisMonth = DB::table('transaction_item')
        ->join('transaction', 'transaction.id', '=', 'transaction_item.transaction_id')
        ->where('transaction.store_id', '=', Auth::user()->store_id)
        ->whereMonth('transaction.date_time', Carbon::now()->month)
        ->whereYear('transaction.date_time', Carbon::now()->year)
        ->sum('transaction_item.quantity');

        $ItemsSoldLastMonth = DB::table('transaction_item')
        ->join('transaction', 'transaction.id', '=', 'transaction_item.transaction_id')
        ->where('transaction.store_id', '=', Auth::user()->store_id)
        ->whereMonth('transaction.date_time', Carbon::now()->subMonth()->month)
        ->whereYear('transaction.date_time', Carbon::now()->year)
        ->sum('transaction_item.quantity');

        $salesThisMonth = DB::table('transaction')
        ->where('store_id', '=',  Auth::user()->store_id)
        ->whereMonth('date_time', Carbon::now()->month)
        ->whereYear('date_time', Carbon::now()->year)
        ->count();

        $salesLastMonth = DB::table('transaction')
        ->where('store_id', '=',  Auth::user()->store_id)
        ->whereMonth('date_time', Carbon::now()->subMonth()->month)
        ->whereYear('date_time', Carbon::now()->year)
        ->count();

        $deliveriesToComplete = DB::table('order')
        ->where('fulfilled', '=', 0)
        ->where('store_id', '=', Auth::User()->store_id)
        ->count();

        $deliveriesMade = DB::table('order')
        ->where('fulfilled', '=', 1)
        ->where('store_id', '=', Auth::User()->store_id)
        ->count();

        //calculate the total number of items ordered in that order
        return (view('dashboard', ['deliveriesMade' => $deliveriesMade,'deliveriesToComplete' => $deliveriesToComplete, 'orderHistory' => $orderHistory, 'numberOfOrders' => $numberOfOrders, 'numberOfSales' => $numberOfSales, 'ItemsSoldThisMonth' => $ItemsSoldThisMonth, 'salesLastMonth' => $salesLastMonth, 'salesThisMonth' => $salesThisMonth, 'ItemsSoldLastMonth' => $ItemsSoldLastMonth]));
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
