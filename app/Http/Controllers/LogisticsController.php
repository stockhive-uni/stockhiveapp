<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogisticsController extends Controller
{
    public function index()
    {
        $orders = DB::table('order')
            ->where('fulfilled', 0)
            ->where('store_id', '=', Auth::user()->store_id)
            ->get();

        return view('logistics.index', compact('orders'));
    }


    public function Showdeliverynotes(Request $request)
    {

        $orderId = $request->input('order');


        $order = DB::table('order')
            ->join('users', 'order.user_id', '=', 'users.id')
            ->select('order.id', 'order.date_time', 'users.first_name', 'users.last_name')
            ->where('order.id', $orderId)
            ->first();


        $orderData = DB::table('order')
            ->join('users', 'order.user_id', '=', 'users.id')
            ->join('order_item', 'order.id', '=', 'order_item.order_id')
            ->join('item', 'order_item.item_id', '=', 'item.id')
            ->join('delivery_note', 'order.id', '=', 'delivery_note.order_id')
            ->join('delivered_item', 'delivery_note.id', '=', 'delivered_item.delivery_note_id')
            ->join('over_deliveries', 'delivery_note.id', '=', 'over_deliveries.delivery_note_id')
            ->select(
                'order.id as order_id',
                'users.first_name',
                'users.last_name',
                'item.id as item_id',
                'item.name as item_name',
                'order_item.ordered as ordered_quantity',
                'delivered_item.quantity as delivered_quantity',
                'over_deliveries.quantity as over_delivered_quantity',
                'over_deliveries.returned',
                'delivery_note.date_time as delivery_date'
            )
            ->where('order.id', $orderId)
            ->get();

        $items = [];
        foreach ($orderData as $data) {
            $itemId = $data->item_id;

            if (!isset($items[$itemId])) {
                $items[$itemId] = [
                    'id' => $data->item_id,
                    'name' => $data->item_name,
                    'ordered' => $data->ordered_quantity,
                    'delivered' => 0,
                    'over_delivered' => 0,
                ];
            }

            $deliveredQuantity = $data->delivered_quantity ?? 0;
            $overDeliveredQuantity = $data->over_delivered_quantity ?? 0;
            $returnedQuantity = $data->returned ? $overDeliveredQuantity : 0;
            
            $items[$itemId]['delivered'] += max(0, $deliveredQuantity - $returnedQuantity);
            $items[$itemId]['over_delivered'] += max(0, $deliveredQuantity - $returnedQuantity - $items[$itemId]['ordered']);

        }

        return view('logistics.showdeliverynotes', compact('order', 'items', 'notesWithItems'));
    }


}