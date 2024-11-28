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


    public function show($orderId)
    {
       
        $order = Order::with(['items.item', 'deliveryNotes.deliveredItems'])->findOrFail($orderId);
    
      
        $items = $order->items->map(function ($orderItem) use ($order) {
            $deliveredQuantity = $order->deliveryNotes->flatMap(function ($note) use ($orderItem) {
                return $note->deliveredItems->where('item_id', $orderItem->item_id);
            })->sum('quantity');
    
            $quantityLeft = $orderItem->ordered - $deliveredQuantity;
    
            return [
                'id' => $orderItem->item_id,
                'name' => $orderItem->item->name,
                'ordered' => $orderItem->ordered,
                'delivered' => $deliveredQuantity,
                'quantity_left' => $quantityLeft,
            ];
        });
    
        
        $allFulfilled = $items->every(function ($item) {
            return $item['quantity_left'] <= 0;
        });
    
   
        if ($allFulfilled && !$order->fulfilled) {
            $order->update(['fulfilled' => 1]);
        }
 
        $notesWithItems = $order->deliveryNotes->map(function ($note) {
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

    return redirect()->route('logistics.show', $id)->with('success', 'Delivery Note created successfully!');
}

}    