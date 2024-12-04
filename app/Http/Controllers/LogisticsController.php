<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\DeliveredItem;
use App\Models\DeliveryNote;
use App\Models\OverDelivery;

class LogisticsController extends Controller
{
    public function index()
    {
        $orders = Order::where('fulfilled', 0)
            ->orderBy('date_time', 'desc')
            ->get();

        return view('logistics.index', compact('orders'));
    }

    public function showOverDeliveries()
    {
        $overDeliveries = OverDelivery::where('returned', 0)
            ->with([
                'deliveryNote.order.orderItems',
                'deliveryNote.deliveredItems',
                'item'
            ])
            ->get();

        return view('logistics.overdelivery', compact('overDeliveries'));
    }



    public function show($orderId)
    {
        $order = Order::with(['user', 'items.item', 'deliveryNotes.deliveredItems'])->findOrFail($orderId);

        $items = $order->items->map(function ($orderItem) use ($order) {
            $deliveredQuantity = $order->deliveryNotes->flatMap(function ($note) use ($orderItem) {
                return $note->deliveredItems->where('item_id', $orderItem->item_id);
            })->sum('quantity');

            $returnedQuantity = OverDelivery::where('item_id', $orderItem->item_id)
                ->whereHas('deliveryNote', function ($query) use ($order) {
                    $query->where('order_id', $order->id);
                })
                ->where('returned', true)
                ->sum('quantity');

            return [
                'id' => $orderItem->item_id,
                'name' => $orderItem->item->name,
                'ordered' => $orderItem->ordered,
                'delivered' => max(0, $deliveredQuantity - $returnedQuantity),
                'over_delivered' => max(0, $deliveredQuantity - $returnedQuantity - $orderItem->ordered),
            ];
        });

        $notesWithItems = $order->deliveryNotes->map(function ($note) {
            $deliveredItems = $note->deliveredItems->map(function ($deliveredItem) use ($note) {
                $overDelivery = OverDelivery::where('delivery_note_id', $note->id)
                    ->where('item_id', $deliveredItem->item_id)
                    ->first();

                return [
                    'name' => $deliveredItem->item->name,
                    'quantity' => $deliveredItem->quantity,
                    'over_delivered' => $overDelivery ? $overDelivery->quantity : 0,
                ];
            });

            return [
                'delivery_note_id' => $note->id,
                'delivery_note_date' => $note->date_time,
                'delivered_items' => $deliveredItems,
            ];
        });

        $totalOverDeliveryQuantity = $items->sum('over_delivered');

        return view('logistics.show', compact('order', 'items', 'notesWithItems', 'totalOverDeliveryQuantity'));
    }

    public function markAsReturned(Request $request)
    {
        $overDeliveries = $request->input('over_deliveries', []);

        DB::beginTransaction();

        try {
            foreach ($overDeliveries as $deliveryNoteId => $items) {
                foreach ($items as $itemId => $returned) {
                    $overDelivery = OverDelivery::where('delivery_note_id', $deliveryNoteId)
                        ->where('item_id', $itemId)
                        ->first();

                    if ($overDelivery && !$overDelivery->returned) {
                        $overDelivery->returned = true;
                        $overDelivery->save();

                        $this->adjustStoreItemStorage($itemId, 5, -$overDelivery->quantity);
                    }
                }
            }

            DB::commit();
            return redirect()->route('logistics.overdelivery')
                ->with('success', 'Selected overdelivered items have been marked as returned and inventory updated.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('logistics.overdelivery')
                ->with('error', 'An error occurred while processing the return.');
        }
    }
    private function adjustStoreItemStorage($itemId, $locationId, $quantity)
    {
        $existingRecord = DB::table('store_item_storage')
            ->where('store_item_id', $itemId)
            ->where('location_id', $locationId)
            ->first();

        if ($existingRecord) {
            $newQuantity = $existingRecord->quantity + $quantity;

            $newQuantity = max(0, $newQuantity);

            DB::table('store_item_storage')
                ->where('store_item_id', $itemId)
                ->where('location_id', $locationId)
                ->update(['quantity' => $newQuantity]);
        }
    }



    private function updateStoreItemStorage($itemId, $locationId, $quantity)
    {
        $existingRecord = DB::table('store_item_storage')
            ->where('store_item_id', $itemId)
            ->where('location_id', $locationId)
            ->first();

        if ($existingRecord) {
            DB::table('store_item_storage')
                ->where('store_item_id', $itemId)
                ->where('location_id', $locationId)
                ->update([
                    'quantity' => $existingRecord->quantity + $quantity,
                ]);
        } else {
            DB::table('store_item_storage')->insert([
                'store_item_id' => $itemId,
                'location_id' => $locationId,
                'quantity' => $quantity,
            ]);
        }
    }


    public function createDeliveryNote(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:item,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        $order = Order::with('items')->findOrFail($id);

        $deliveryNote = DeliveryNote::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'date_time' => now(),
        ]);

        foreach ($validated['items'] as $item) {
            $orderItem = $order->items->where('item_id', $item['id'])->first();
            $orderedQuantity = $orderItem->ordered;

            $deliveredBefore = DeliveredItem::whereHas('deliveryNote', function ($query) use ($order) {
                $query->where('order_id', $order->id);
            })->where('item_id', $item['id'])->sum('quantity');

            $remainingToOrder = max(0, $orderedQuantity - $deliveredBefore);

            $deliveredQuantity = $item['quantity'];
            $overDeliveredForCurrentNote = max(0, $deliveredQuantity - $remainingToOrder);
            $validDeliveryToWarehouse = $deliveredQuantity - $overDeliveredForCurrentNote;

            if ($deliveredQuantity > 0) {
                DeliveredItem::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'item_id' => $item['id'],
                    'quantity' => $deliveredQuantity,
                ]);

                if ($validDeliveryToWarehouse > 0) {
                    $this->updateStoreItemStorage($item['id'], 3, $validDeliveryToWarehouse);
                }
            }

            if ($overDeliveredForCurrentNote > 0) {
                OverDelivery::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'item_id' => $item['id'],
                    'store_id' => $order->store_id,
                    'returned' => false,
                    'quantity' => $overDeliveredForCurrentNote,
                    'date_time' => now(),
                ]);

                $this->updateStoreItemStorage($item['id'], 5, $overDeliveredForCurrentNote);
            }
        }

        return redirect()->route('logistics.show', ['id' => $id])
            ->with('success', 'Delivery Note Created and Overdeliveries Recorded.');
    }

    public function storeOverDelivery($deliveryNoteId, $itemId, $deliveredQuantity, $storeId)
    {

        $deliveryNote = DeliveryNote::findOrFail($deliveryNoteId);

        $orderedQuantity = $deliveryNote->order
            ->orderItems
            ->firstWhere('item_id', $itemId)
            ->ordered ?? 0;


        $quantityLeft = $orderedQuantity - $deliveredQuantity;

        $overDeliveredQuantity = $quantityLeft <= 0
            ? $deliveredQuantity
            : abs($quantityLeft - $deliveredQuantity);


        \Log::info('OverDelivery Debug: ' . json_encode([
            'item_id' => $itemId,
            'ordered_quantity' => $orderedQuantity,
            'delivered_quantity' => $deliveredQuantity,
            'over_delivered_quantity' => $overDeliveredQuantity,
        ]));

        if ($overDeliveredQuantity > 0) {
            OverDelivery::updateOrCreate(
                [
                    'delivery_note_id' => $deliveryNoteId,
                    'item_id' => $itemId,
                    'store_id' => $storeId,
                ],
                [
                    'quantity' => $overDeliveredQuantity,
                    'returned' => false,
                    'date_time' => now(),
                ]
            );
        }
    }



    public function processDelivery(Request $request)
    {
        $deliveryNoteId = $request->input('delivery_note_id');
        $items = $request->input('items');
        $storeId = $request->input('store_id');

        foreach ($items as $itemId => $deliveredQuantity) {
            $this->storeOverDelivery($deliveryNoteId, $itemId, $deliveredQuantity, $storeId);
        }

        return redirect()->route('logistics.overdelivery')->with('success', 'Deliveries processed successfully.');
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
        $request->validate([
            'over_deliveries' => 'required|array',
            'over_deliveries.*' => 'exists:over_deliveries,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->over_deliveries as $overDeliveryId => $value) {
                $overDelivery = OverDelivery::find($overDeliveryId);

                if ($overDelivery && !$overDelivery->returned) {
                    $overDelivery->returned = true;
                    $overDelivery->save();
                }
            }

            DB::commit();
            return redirect()->route('logistics.overdelivery')
                ->with('success', 'Selected overdeliveries marked as returned.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('logistics.overdelivery')
                ->with('error', 'An error occurred while processing the return.');
        }
    }


}
