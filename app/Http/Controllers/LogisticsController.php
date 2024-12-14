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



    public function show(Request $request)
    {
        $orderId = $request->input('order');
        if (!$orderId) {
            return redirect()->route('logistics');
        }

        $order = Order::with(['user', 'items.item', 'deliveryNotes.deliveredItems'])->findOrFail($orderId);

        $overDeliveries = OverDelivery::whereHas('deliveryNote', function ($query) use ($order) {
            $query->where('order_id', $order->id);
        })
            ->where('returned', true)
            ->get();

        $items = $order->items->map(function ($orderItem) use ($order, $overDeliveries) {
            $deliveredQuantity = $order->deliveryNotes->flatMap(function ($note) use ($orderItem) {
                return $note->deliveredItems->where('item_id', $orderItem->item_id);
            })->sum('quantity');


            $returnedQuantity = $overDeliveries
                ->where('item_id', $orderItem->item_id)
                ->sum('quantity');

            return [
                'id' => $orderItem->item_id,
                'name' => $orderItem->item->name,
                'ordered' => $orderItem->ordered,
                'delivered' => max(0, $deliveredQuantity - $returnedQuantity),
                'over_delivered' => max(0, $deliveredQuantity - $returnedQuantity - $orderItem->ordered),
            ];
        });


        $notesWithItems = $order->deliveryNotes->map(function ($note) use ($overDeliveries) {
            $deliveredItems = $note->deliveredItems->map(function ($deliveredItem) use ($note, $overDeliveries) {
                $overDelivery = $overDeliveries->firstWhere(function ($od) use ($note, $deliveredItem) {
                    return $od->delivery_note_id === $note->id && $od->item_id === $deliveredItem->item_id;
                });

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





    private function updateStorage($itemId, $locationId, $quantity, $ensureNonNegative = false)
    {
        $existingRecord = DB::table('store_item_storage')
            ->where('store_item_id', $itemId)
            ->where('location_id', $locationId)
            ->first();

        $newQuantity = $existingRecord ? $existingRecord->quantity + $quantity : $quantity;

        if ($ensureNonNegative) {
            $newQuantity = max(0, $newQuantity);
        }

        if ($existingRecord) {
            DB::table('store_item_storage')
                ->where('store_item_id', $itemId)
                ->where('location_id', $locationId)
                ->update(['quantity' => $newQuantity]);
        } else {
            DB::table('store_item_storage')->insert([
                'store_item_id' => $itemId,
                'location_id' => $locationId,
                'quantity' => $newQuantity,
            ]);
        }
    }

    public function createDeliveryNote(Request $request)
    {
        $orderId = $request->input('order_id');

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:item,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        $order = Order::with('items')->findOrFail($orderId);

        $deliveryNote = DeliveryNote::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'date_time' => now(),
        ]);

        foreach ($validated['items'] as $item) {
            $orderItem = $order->items->where('item_id', $item['id'])->first();

            $quantities = $this->calculateDeliveryQuantities(
                $orderItem->ordered,
                DeliveredItem::whereHas('deliveryNote', function ($query) use ($order) {
                    $query->where('order_id', $order->id);
                })->where('item_id', $item['id'])->sum('quantity'),
                $item['quantity']
            );

            if ($item['quantity'] > 0) {
                DeliveredItem::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);

                if ($quantities['valid_delivery'] > 0) {
                    $this->updateStorage($item['id'], 3, $quantities['valid_delivery']);
                }
            }

            if ($quantities['over_delivery'] > 0) {
                OverDelivery::create([
                    'delivery_note_id' => $deliveryNote->id,
                    'item_id' => $item['id'],
                    'store_id' => $order->store_id,
                    'returned' => false,
                    'quantity' => $quantities['over_delivery'],
                    'date_time' => now(),
                ]);

                $this->updateStorage($item['id'], 5, $quantities['over_delivery']);
            }
        }

        $isFulfilled = $order->items->every(function ($orderItem) use ($order) {
            $deliveredQuantity = $order->deliveryNotes->flatMap(function ($note) use ($orderItem) {
                return $note->deliveredItems->where('item_id', $orderItem->item_id);
            })->sum('quantity');

            return $deliveredQuantity >= $orderItem->ordered;
        });

        if ($isFulfilled) {
            $order->fulfilled = 1;
            $order->save();

            return redirect()->route('logistics')
                ->with('success', 'Order fulfilled and Delivery Note created successfully.');
        }

        return redirect()->route('logistics.show', ['id' => $orderId])
            ->with('success', 'Delivery Note created successfully.');
    }


    private function calculateDeliveryQuantities($orderedQuantity, $deliveredBefore, $deliveredQuantity)
    {
        $remainingToOrder = max(0, $orderedQuantity - $deliveredBefore);
        $overDelivered = max(0, $deliveredQuantity - $remainingToOrder);
        $validDelivery = $deliveredQuantity - $overDelivered;

        return [
            'remaining_to_order' => $remainingToOrder,
            'over_delivery' => $overDelivered,
            'valid_delivery' => $validDelivery,
        ];
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

    public function markAsReturned(Request $request)
    {
        $overDeliveries = $request->input('over_deliveries', []);
        DB::transaction(function () use ($overDeliveries) {
            $this->markOverDeliveriesAsReturned($overDeliveries);
        });

        return redirect()->route('logistics.overdelivery')
            ->with('success', 'Selected overdelivered items have been marked as returned and inventory updated.');
    }

    public function returnOverDeliveries(Request $request)
    {
        $request->validate(['over_deliveries' => 'required|array']);
        DB::transaction(function () use ($request) {
            $this->markOverDeliveriesAsReturned($request->input('over_deliveries'));
        });

        return redirect()->route('logistics.overdelivery')
            ->with('success', 'Selected overdeliveries marked as returned.');
    }

    public function returnedOverDeliveries()
    {
        $returnedOverDeliveries = OverDelivery::where('returned', true)
            ->with(['deliveryNote', 'item', 'store'])
            ->get();

        return view('logistics.returned-overdeliveries', compact('returnedOverDeliveries'));
    }

    private function markOverDeliveriesAsReturned(array $overDeliveries)
    {
        foreach ($overDeliveries as $deliveryNoteId => $items) {
            foreach ($items as $itemId => $returned) {
                $overDelivery = OverDelivery::where('delivery_note_id', $deliveryNoteId)
                    ->where('item_id', $itemId)
                    ->first();

                if ($overDelivery && !$overDelivery->returned) {
                    $overDelivery->returned = true;
                    $overDelivery->save();

                    $this->updateStorage($itemId, 5, -$overDelivery->quantity, true);
                }
            }
        }
    }




}
