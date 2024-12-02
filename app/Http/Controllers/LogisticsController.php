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
        $overDeliveries = OverDelivery::where('returned', false)
            ->with(['deliveryNote', 'item', 'store'])
            ->get();

        return view('logistics.overdelivery', compact('overDeliveries'));
    }

    public function show($orderId)
    {
        $order = Order::with(['items.item', 'deliveryNotes.deliveredItems'])->findOrFail($orderId);

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
                'delivered' => $deliveredQuantity,
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

        foreach ($overDeliveries as $deliveryNoteId => $items) {
            foreach ($items as $itemId => $returned) {

                $overDelivery = OverDelivery::where('delivery_note_id', $deliveryNoteId)
                    ->where('item_id', $itemId)
                    ->first();

                if ($overDelivery) {
                    $overDelivery->returned = true;
                    $overDelivery->save();
                }
            }
        }
        return redirect()->route('logistics.overdelivery')->with('success', 'Selected overdelivered items have been marked as returned.');
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
            DeliveredItem::create([
                'delivery_note_id' => $deliveryNote->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity'],
            ]);

            $orderItem = $order->items->where('item_id', $item['id'])->first();
            $orderedQuantity = $orderItem->ordered;

            $deliveredBefore = DeliveredItem::whereHas('deliveryNote', function ($query) use ($order) {
                $query->where('order_id', $order->id);
            })->where('item_id', $item['id'])->sum('quantity');

            $remainingQuantity = max(0, $orderedQuantity - $deliveredBefore);
            $overDeliveredForCurrentNote = max(0, $item['quantity'] - $remainingQuantity);

            if ($overDeliveredForCurrentNote > 0) {
                OverDelivery::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'item_id' => $item['id'],
                    'store_id' => $order->store_id,
                    'returned' => false,
                    'quantity' => $overDeliveredForCurrentNote,
                    'date_time' => now(),
                ]);
            }
        }

        return redirect()->route('logistics.show', ['id' => $id])
            ->with('success', 'Delivery Note Created and Overdeliveries Recorded.');
    }

    public function storeOverDelivery(Request $request)
    {
        $validated = $request->validate([
            'delivery_note_id' => 'required|exists:delivery_notes,id',
            'item_id' => 'required|exists:item,id',
            'store_id' => 'required|exists:stores,id',
            'returned' => 'required|boolean',
            'quantity' => 'required|integer|min:1',
            'date_time' => 'required|date',
        ]);

        OverDelivery::create([
            'delivery_note_id' => $validated['delivery_note_id'],
            'item_id' => $validated['item_id'],
            'store_id' => $validated['store_id'],
            'returned' => $validated['returned'],
            'quantity' => $validated['quantity'],
            'date_time' => $validated['date_time'],
        ]);

        return redirect()->route('logistics.overdelivery')->with('success', 'Overdelivery recorded successfully.');
    }

    public function overdelivery()
    {

        $overDeliveries = OverDelivery::where('returned', false)
            ->with(['deliveryNote', 'item', 'store'])
            ->get();

        return view('logistics.overdelivery', compact('overDeliveries'));
    }

    public function returnedItems()
    {
        $returnedItems = OverDelivery::where('returned', true)
            ->with(['deliveryNote', 'item', 'store'])
            ->get();

        return view('logistics.returned', compact('returnedItems'));
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
