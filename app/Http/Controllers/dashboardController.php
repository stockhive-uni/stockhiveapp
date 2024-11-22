<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\warehouseOrder;
use Illuminate\Support\Facades\Auth;

class dashboardController extends Controller
{
    //

    public function index() {

        //fetches the order history related to the store, with the user who made the order, and the items contained in the order
        $orderHistory = warehouseOrder::with(['users', 'order_items'])->where('store_id', Auth::User()->store_id)->get();

        //calculate the total number of items ordered in that order
        return (view('dashboard', ['orderHistory' => $orderHistory]));
    } 
}
