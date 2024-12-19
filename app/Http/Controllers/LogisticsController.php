<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeliveryNote;
use App\Models\OverDelivery;
use Illuminate\Http\Request;
use App\Models\DeliveredItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogisticsController extends Controller
{
    public function index()
    {
        // Displays orders that aren't fulfilled 
        $orders = Order::where('fulfilled', 0)
            ->where('store_id', '=', Auth::user()->store_id)
            ->with('user') 
            ->orderBy('date_time', 'desc')
            ->get();

        return view('logistics.index', compact('orders'));
    }

    public function showDeliveryNotes(Request $request)
    {
        $orderId = $request->input('order');

        if (!$orderId) {
        return redirect()->route('logistics')->with('error', 'Order ID is required');
    }

        // Get order with related data
        $order = Order::with(['user', 'items.item', 'deliveryNotes.deliveredItems'])->findOrFail($orderId);//https://laravel.com/docs/11.x/eloquent

        // Get over deliveries for the order
        $overDeliveries = OverDelivery::join('delivery_note', 'over_deliveries.delivery_note_id', '=', 'delivery_note.id')
            ->where('delivery_note.order_id', $order->id)
            ->get();

        // Process each item for the order
        $items = [];
        foreach ($order->items as $orderItem) {
            $deliveredQuantity = 0;
            // Sum delivered items across all delivery notes for the item
            foreach ($order->deliveryNotes as $note) {
                $deliveredQuantity += $note->deliveredItems->where('item_id', $orderItem->item_id)->sum('quantity');//https://stackoverflow.com/questions/30424949/laravel-query-builder-sum-method-issue
            }

            // Sum over-delivery items for the item
            $overDeliveredQuantity = $overDeliveries->where('item_id', $orderItem->item_id)->sum('quantity');//https://laravel.com/docs/11.x/queries

            // Adjust for returned over-delivered items
            $returnedQuantity = $overDeliveries->where('item_id', $orderItem->item_id)->where('returned', true)->sum('quantity');

            $items[] = [
                'id' => $orderItem->item_id,
                'name' => $orderItem->item->name,
                'ordered' => $orderItem->ordered,
                'delivered' => max(0, $deliveredQuantity - $returnedQuantity),
                'over_delivered' => max(0, $overDeliveredQuantity - $returnedQuantity),
            ];
        }

        // Get delivery notes with items and over deliveries
        $notesWithItems = [];
        foreach ($order->deliveryNotes as $note) {
            $deliveredItems = [];
            foreach ($note->deliveredItems as $deliveredItem) {
                // Get the corresponding over-delivery for the item
                foreach ($overDeliveries as $od) {
                    if ($od->delivery_note_id === $note->id && $od->item_id === $deliveredItem->item_id) {
                        $overDelivery = $od;
                        break;  
                    }
                }
        
                // If an over-delivery exists, retrieve its quantity
                $overDeliveredQuantity = $overDelivery ? $overDelivery->quantity : 0;

                $deliveredItems[] = [
                    'name' => $deliveredItem->item->name,
                    'quantity' => $deliveredItem->quantity,
                    'over_delivered' => $overDeliveredQuantity,
                ];
            }

            $notesWithItems[] = [
                'delivery_note_id' => $note->id,
                'delivery_note_date' => $note->date_time,
                'delivered_items' => $deliveredItems,
            ];
        }

        return view('logistics.showdeliverynotes', compact('order', 'items', 'notesWithItems'));
    }

    public function createDeliveryNote(Request $request)
    {
        // Validate input
        $orderId = $request->input('order_id');
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:item,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        // Get the order and create the delivery note
        $order = Order::with('items')->findOrFail($orderId);
        DB::table('delivery_note')->insert([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'date_time' => now(),
        ]);
        $deliveryNoteId = DB::getPdo()->lastInsertId();//https://stackoverflow.com/questions/49212386/how-to-get-last-id-inserted-on-a-database-in-laravel

        // Process each item in the order
        foreach ($validated['items'] as $item) {
            if ($item['quantity'] <= 0) {
                continue;
            }

            $orderItem = $order->items->where('item_id', $item['id'])->first();

            // Calculate the delivered quantity so far
            $deliveredQuantity = DeliveredItem::join('delivery_note', 'delivered_item.delivery_note_id', '=', 'delivery_note.id')
            ->where('delivery_note.order_id', $order->id)
            ->where('delivered_item.item_id', $item['id'])
            ->sum('delivered_item.quantity');
        

            // Calculate remaining quantity needed for the order
            $remainingNeeded = max(0, $orderItem->ordered - $deliveredQuantity);


            if ($remainingNeeded > 0) {
                // Some items still need to be delivered
                $validDelivery = min($item['quantity'], $remainingNeeded);
                $overDelivered = max(0, $item['quantity'] - $validDelivery);
            } else {
                // Order is already fulfilled, everything is over delivery
                $validDelivery = 0;
                $overDelivered = $item['quantity'];
            }

            
            if ($validDelivery > 0) {
                DB::table('delivered_item')->insert([
                    'delivery_note_id' => $deliveryNoteId,
                    'item_id' => $item['id'],
                    'quantity' => $validDelivery,
                ]);

                // Adds to DeliveredItem table and update  warehouse
                $existingRecord = DB::table('store_item_storage')
                    ->where('store_item_id', $item['id'])
                    ->where('location_id', 3)
                    ->first();

                $newQuantity = $existingRecord ? $existingRecord->quantity + $validDelivery : $validDelivery;//https://www.php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary
                $newQuantity = max(0, $newQuantity);

                if ($existingRecord) {
                    DB::table('store_item_storage')
                        ->where('store_item_id', $item['id'])
                        ->where('location_id', 3)
                        ->update(['quantity' => $newQuantity]);
                } else {
                    DB::table('store_item_storage')->insert([
                        'store_item_id' => $item['id'],
                        'location_id' => 3,
                        'quantity' => $newQuantity,
                    ]);
                }
            }

          
            if ($overDelivered > 0) {
                DB::table('over_deliveries')->insert([
                    'delivery_note_id' => $deliveryNoteId,
                    'item_id' => $item['id'],
                    'store_id' => $order->store_id,
                    'returned' => false,
                    'quantity' => $overDelivered,
                    'date_time' => now(),
                ]);

                // Update store storage for over-delivery return bay
                $existingRecord = DB::table('store_item_storage')
                    ->where('store_item_id', $item['id'])
                    ->where('location_id', 5)
                    ->first();

                    if ($existingRecord) {
                        $newQuantity = $existingRecord->quantity + $overDelivered;
                    } else {
                        $newQuantity = $overDelivered;
                    }
                $newQuantity = max(0, $newQuantity);



                if ($existingRecord) {
                    DB::table('store_item_storage')
                        ->where('store_item_id', $item['id'])
                        ->where('location_id', 5)
                        ->update(['quantity' => $newQuantity]);
                } else {
                    DB::table('store_item_storage')->insert([
                        'store_item_id' => $item['id'],
                        'location_id' => 5,
                        'quantity' => $newQuantity,
                    ]);
                }
            }
        }

        // Now check if the order is fully fulfilled
        $fulfilled = true;
        foreach ($order->items as $orderItem) {
            $deliveredQuantity = DeliveredItem::whereHas('deliveryNote', function ($query) use ($order) {
                $query->where('order_id', $order->id);
            })->where('item_id', $orderItem->item_id)->sum('quantity');

            if ($deliveredQuantity < $orderItem->ordered) {
                $fulfilled = false;
                break;
            }
        }

        // If all items are delivered, mark the order as fulfilled
        if ($fulfilled) {
            $order->fulfilled = 1;
            $order->save();
        }

        // Redirect to the order page or delivery notes list with a success message
        return redirect()->route('logistics.showdeliverynotes', $order->id)->with('success', 'Delivery Note created successfully!');
    }

    public function showOverDeliveries()
    {
        $overDeliveries = OverDelivery::where('returned', 0)
            ->where('store_id', Auth::user()->store_id)
            ->get();

        return view('logistics.overdeliveries', compact('overDeliveries'));
    }
}
