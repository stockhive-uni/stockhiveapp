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
        $orders = Order::where('fulfilled', 0) 
            ->orderBy('date_time', 'desc')
            ->get();

        return view('logistics.index', compact('orders'));  
    }


public function show($id)
{
    $order = Order::with(['items'])->findOrFail($id);

    $deliveryNotes = DeliveryNote::where('order_id', $id)
        ->with(['deliveredItems.item'])
        ->get();

    $notesWithItems = $deliveryNotes->map(function ($note) {
        return [
            'delivery_note_id' => $note->id,
            'delivery_note_date' => $note->date_time,
            'delivered_items' => $note->deliveredItems->map(function ($deliveredItem) {
                return [
                    'name' => $deliveredItem->item->name,
                    'quantity' => $deliveredItem->quantity,
                ];
            }),
        ];
    });

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

    return view('logistics.show', compact('order', 'items', 'notesWithItems'));
}

    
public function createDeliveryNote(Request $request, $id)
{
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.id' => 'required|exists:item,id', 
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

    return redirect()->route('logistics.show', $order->id)
        ->with('success', 'Delivery Note created successfully!');
}
}