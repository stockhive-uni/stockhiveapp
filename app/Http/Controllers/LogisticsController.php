<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $overDeliveries = OverDelivery::with(['deliveryNote', 'item', 'store'])
            ->where('returned', false)
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
            $overDeliveredQuantity = max(0, $deliveredQuantity - $orderItem->ordered);
            return [
                'id' => $orderItem->item_id,
                'name' => $orderItem->item->name,
                'ordered' => $orderItem->ordered,
                'delivered' => $deliveredQuantity,
                'over_delivered' => $overDeliveredQuantity,
            ];
        });

        $allFulfilled = $items->every(function ($item) {
            return $item['delivered'] >= $item['ordered'];
        });

        if ($allFulfilled && !$order->fulfilled) {
            $order->update(['fulfilled' => 1]);
            return redirect()->route('logistics')->with('success', 'Order fully fulfilled!');
        }


        $overDeliveries = OverDelivery::where('returned', false)
            ->whereIn('delivery_note_id', $order->deliveryNotes->pluck('id'))
            ->get();

        $totalOverDeliveryQuantity = $overDeliveries->sum('quantity');

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
        return view('logistics.show', compact('order', 'items', 'notesWithItems', 'totalOverDeliveryQuantity'));
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
            $totalDeliveredAfter = DeliveredItem::whereHas('deliveryNote', function ($query) use ($order) {
                $query->where('order_id', $order->id);
            })->where('item_id', $item['id'])->sum('quantity');


            $overDeliveredQuantity = max(0, $totalDeliveredAfter - $orderedQuantity);

            if ($overDeliveredQuantity > 0) {
                $previousOverDeliveredQuantity = OverDelivery::where('item_id', $item['id'])
                    ->whereHas('deliveryNote', function ($query) use ($order) {
                        $query->where('order_id', $order->id);
                    })
                    ->sum('quantity');
                $newOverDeliveredQuantity = $overDeliveredQuantity - $previousOverDeliveredQuantity;

                if ($newOverDeliveredQuantity > 0) {
                    OverDelivery::create([
                        'delivery_note_id' => $deliveryNote->id,
                        'item_id' => $item['id'],
                        'store_id' => $order->store_id,
                        'returned' => false,
                        'quantity' => $newOverDeliveredQuantity,
                        'date_time' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('logistics.show', ['id' => $id])->with('success', 'Delivery Note Created and Over Deliveries Recorded');
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

}
