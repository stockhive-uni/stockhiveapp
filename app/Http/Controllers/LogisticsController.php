<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\DeliveredItem;
use App\Models\DeliveryNote;

class LogisticsController extends Controller
{
  
    
    public function index()
    {
        $orders = Order::where('fulfilled', 0)->orderBy('date_time', 'desc')->get();

        return view('Logistics.index', compact('orders'));
    }
    public function show($id)
{
    $order = Order::with(['items.item', 'items.deliveredItems'])->findOrFail($id);

    $items = $order->items->map(function ($orderItem) {
        $deliveredQuantity = $orderItem->deliveredItems->sum('quantity');
        $quantityLeft = $orderItem->ordered - $deliveredQuantity;

        return [
            'id' => $orderItem->item_id,
            'name' => $orderItem->item->name,
            'ordered' => $orderItem->ordered,
            'delivered' => $deliveredQuantity,
            'quantity_left' => $quantityLeft,
        ];
    });

    return view('logistics.show', compact('order', 'items'));
}

public function createDeliveryNote(Request $request, $id)
{
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.id' => 'required|exists:items,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $order = Order::findOrFail($id);

    $deliveryNote = DeliveryNote::create([
        'user_id' => auth()->id(),
        'order_id' => $order->id,
        'date_time' => now(),
    ]);

    foreach ($validated['items'] as $item) {
        DeliveredItem::create([
            'delivery_note_id' => $deliveryNote->id,
            'item_id' => $item['id'],
            'quantity' => $item['quantity'],
        ]);
    }

    return redirect()->route('logistics.index')->with('success', 'Delivery Note created successfully!');
}
}