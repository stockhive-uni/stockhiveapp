<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeliveryNote;
use App\Models\OverDelivery;
use App\Models\store_item;
use App\Models\store_item_storage;
use Illuminate\Http\Request;
use App\Models\DeliveredItem;
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

        // Get over deliveries for the order using laravel query builderhttps://laravel.com/docs/11.x/queries#joins
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
                'delivered' => max(0, $deliveredQuantity),
                'over_delivered' => max(0, $overDeliveredQuantity - $returnedQuantity),
            ];
        }

        // Get delivery notes with items and over deliveries
        $notesWithItems = [];
        foreach ($order->deliveryNotes as $note) {
            $deliveredItems = [];
            foreach ($note->deliveredItems as $deliveredItem) {
                $overDelivery = null;
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
        
        $orderId = $request->input('order_id');
        $validated = $request->validate([ //laravel validation https://laravel.com/docs/11.x/validation
            'items' => 'required|array',
            'items.*' => 'exclude_if:items.*.quantity,0',// exclude_if part https://laravel.com/docs/11.x/validation#rule-exclude-if
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.id' => 'required|exists:item,id'
        ]);

        // Get the order and create the delivery note
        $order = Order::with('items')->findOrFail($orderId);

        $deliveryNoteId = DeliveryNote::insertGetId // Auto incrementing https://laravel.com/docs/11.x/queries#inserts
        (
            [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'date_time' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $fulfilled = true;
        foreach ($order->items as $orderItem) {

            // Calculate the delivered quantity so far
            $deliveredQuantity = DeliveryNote::where('delivery_note.order_id', '=', $order->id)
                ->join('delivered_item', 'delivered_item.delivery_note_id', '=', 'delivery_note.id')
                ->where('delivered_item.item_id', $orderItem->item_id)
                ->sum('delivered_item.quantity');//sum documentation https://laravel.com/docs/11.x/queries#aggregates

            // Check if item is in delivery note being created
            if (isset($validated['items'][$orderItem->item_id])) {
                $noteItem = $validated['items'][$orderItem->item_id];
                $storeItem = store_item::where('store_item.store_id', '=', $order->store_id)
                    ->where('store_item.item_id', '=', $noteItem['id'])
                    ->first();

                // Calculate remaining quantity needed for the order
                $remainingNeeded = max(0, $orderItem->ordered - $deliveredQuantity);
                $forWarehouse = min($remainingNeeded, $noteItem['quantity']);// Min() function: https://www.php.net/manual/en/function.min.php
                $forReturn = $noteItem['quantity'] - $forWarehouse;

                DeliveredItem::insert([
                    'delivery_note_id' => $deliveryNoteId,
                    'item_id' => $noteItem['id'],
                    'quantity' => $forWarehouse,
                ]);

                $deliveredQuantity += $noteItem['quantity'];

                if ($forWarehouse > 0) {
                    // Location id hardcoded to 3 for warehouse
                    $storeItemStorage = store_item_storage::where('store_item_storage.store_item_id', '=', $storeItem->id)
                        ->where('store_item_storage.location_id', '=', 3)
                        ->first();

                    if (is_null($storeItemStorage)) {
                        store_item_storage::insert(
                            [
                                'store_item_id' => $storeItem->id,
                                'quantity' => $forWarehouse,
                                'location_id' => 3
                            ]
                        );
                    } else {
                        $storeItemStorage->quantity += $forWarehouse;
                        $storeItemStorage->save();// Save method: https://laravel.com/docs/11.x/eloquent#updates
                    }
                }
                ;

                if ($forReturn > 0) {
                    OverDelivery::insert(
                        [
                            'delivery_note_id' => $deliveryNoteId,
                            'item_id' => $noteItem['id'],
                            'store_id' => $order->store_id,
                            'returned' => false,
                            'quantity' => $forReturn,
                            'date_time' => now()
                        ]
                    );

                    // Location id hardcoded to 5 for return bay
                    $storeItemStorage = store_item_storage::where('store_item_storage.store_item_id', '=', $storeItem->id)
                        ->where('store_item_storage.location_id', '=', value: 5)
                        ->first();

                    if (is_null($storeItemStorage)) {
                        store_item_storage::insert(
                            [
                                'store_item_id' => $storeItem->id,
                                'quantity' => $forReturn,
                                'location_id' => 5
                            ]
                        );
                    } else {
                        $storeItemStorage->quantity += $forReturn;
                        $storeItemStorage->save();
                    }
                }
                ;
            }

            if ($deliveredQuantity < $orderItem->ordered) {
                $fulfilled = false;
            }
            ;
        }

        if ($fulfilled) {
            $order->fulfilled = true;
            $order->save();
            return redirect()->route('logistics', [])->with('success', 'order fully fulfilled');
        }

    
        return redirect()->route('logistics.showdeliverynotes', ['order' => $order->id])->with('success', 'Delivery Note created successfully!');
    }

    public function showOverDeliveries()
    {

        $overDeliveries = OverDelivery::with([
            'deliveryNote.order.orderItems',
            'deliveryNote.deliveredItems'
        ])
            ->where('returned', 0)
            ->where('store_id', Auth::user()->store_id)
            ->get();

        return view('logistics.overdelivery', compact('overDeliveries'));
    }

    public function returnedOverDeliveries()
    {
        $returnedOverDeliveries = OverDelivery::where('returned', true)
            ->with(['deliveryNote', 'item', 'store'])
            ->get();

        return view('logistics.returned-overdeliveries', compact('returnedOverDeliveries'));
    }
    public function returnOverDeliveries(Request $request)
    {
        // Validate the structure of the incoming request
        $validated = $request->validate([
            'over_deliveries' => 'required|array',
            'over_deliveries.*' => 'exclude_without:over_deliveries.*.selected',
        ]);


        // If no items selected exit early
        if (empty($validated['over_deliveries'])) {
            return redirect()->route('logistics.overdelivery')
                ->with('success', 'No over deliveries selected!');
        }

       

        // Iterate through each overdelivery and process the data
        foreach ($validated['over_deliveries'] as $overDelivery) {
            // Fetch the overdelivery object for this specific delivery note and item
            $overDelivery = OverDelivery::where('over_deliveries.delivery_note_id', '=', $overDelivery['delivery_note_id'])
                ->where('over_deliveries.item_id', '=', $overDelivery['item_id'])
                ->where('over_deliveries.store_id', '=', $overDelivery['store_id'])
                ->first();

            $storeItemId = Store_Item::where('store_item.store_id', $overDelivery->store_id)
                ->where('store_item.item_id', '=', $overDelivery->item_id)
                ->value('id');

            // Decrement the quantity in store_item_storage
            Store_Item_Storage::where('store_item_storage.store_item_id', '=', $storeItemId)
                ->where('store_item_storage.location_id', 5)
                ->decrement('store_item_storage.quantity', $overDelivery->quantity);

            // Mark the overdelivery as returned
            OverDelivery::where('over_deliveries.delivery_note_id', '=', $overDelivery->delivery_note_id)
                ->where('over_deliveries.item_id', '=', $overDelivery->item_id)
                ->where('over_deliveries.store_id', '=', $overDelivery->store_id)
                ->update(['returned' => true]);
        }
        ;

        return redirect()->route('logistics.overdelivery')
            ->with('success', 'Selected overdelivered items have been marked as returned and inventory updated.');
    }
}