<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    // List all orders
    public function index()
    {
        $orders = Order::orderBy('date_time', 'desc')->get(); 
        return view('Logistics.logistics', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id); 
        $items = $order->items; 
        return view('logistics.show', compact('order', 'items'));
    }
}
