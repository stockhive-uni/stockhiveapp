<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    public function index()
    {
        $items = DB::table('transaction')
            ->join('users', 'transaction.user_id', '=', 'users.id')
            ->where('transaction.store_id', Auth::User()->store_id)
            ->select('transaction.id', 'users.first_name', 'users.last_name', 'transaction.date_time')
            ->get();
        return view('Sales.index', ['items' => $items]);
    }

    public function startSale() {
        $items = DB::table('item')
            ->join('store_item', 'store_item.item_id', '=', 'item.id')
            ->where('store_item.store_id', '=', Auth::User()->store_id)
            ->select('item.id', 'item.name', 'store_item.price')
            ->get();

        return view('Sales.sales', compact('items'));
    }

    public function viewDetails(Request $request) {
        $id = $request->id;

        $transaction = Transaction::where('transaction.id', $id)
            ->join('users', 'transaction.user_id', '=', 'users.id')
            ->join('store', 'transaction.store_id', '=', 'store.id')
            ->select('transaction.id', 'users.first_name', 'users.last_name', 'transaction.date_time', 'transaction.card', 'store.location')
            ->first();

        $items = DB::table('transaction_item')
            ->where('transaction_id', $id)
            ->join('item', 'item.id', '=', 'transaction_item.item_id')
            ->select('transaction_item.quantity', 'transaction_item.price', 'item.name')
            ->get();
            
        return view('Sales.transaction-details', compact('transaction', 'items'));
    }

    public function downloadInvoice(Request $request) {
        // https://bagisto.com/en/how-to-generate-a-pdf-in-laravel-view/ - Rob

        $id = $request->id;

        $transaction = Transaction::where('transaction.id', $id)
            ->join('users', 'transaction.user_id', '=', 'users.id')
            ->join('store', 'transaction.store_id', '=', 'store.id')
            ->select('transaction.id', 'users.first_name', 'users.last_name', 'transaction.date_time', 'transaction.card', 'store.location')
            ->first();

        $items = DB::table('transaction_item')
            ->where('transaction_id', $id)
            ->join('item', 'item.id', '=', 'transaction_item.item_id')
            ->select('transaction_item.quantity', 'transaction_item.price', 'item.name')
            ->get();

        $pdf = Pdf::loadView('Sales.invoice', compact('transaction', 'items'));

        // Stream the PDF to the browser for download
        return $pdf->download('invoice-' . $id . '.pdf');
    }

    public function confirmTransaction(Request $request) {
        // Get items from request
        $ids = $request->id;
        $quantities = $request->quantity;
        
        // Checks if order is null
        $message = null;
        if (isset($ids)) {
            // Ensure they are numbers and not strings
            $ids = array_map('intval', $ids);
            $quantities = array_map('intval', $quantities);

            $totals = [];

            // Creates a dictionary for duplicate items
            foreach ($ids as $index => $id) {
                if (isset($totals[$id])) {
                    $totals[$id] += $quantities[$index];
                } else {
                    $totals[$id] = $quantities[$index];
                }
            }

            $randomCard = random_int(1000000000000000, 9999999999999999);
            $lastId = DB::table('transaction')->insertGetId([
                'user_id' => Auth::User()->id,
                'store_id' => Auth::User()->store_id,
                'card' => $randomCard
            ]);

            // Inserts for each item bought
            foreach ($totals as $key => $value) {
                $price = DB::table('item')
                    ->join('store_item', 'store_item.item_id', '=', 'item.id')
                    ->select('store_item.price')
                    ->where ('item.id', $key)
                    ->first();

                DB::table('transaction_item')->insert([
                    'transaction_id' => $lastId,
                    'item_id' => $key,
                    'quantity' => $value,
                    'price' => $price->price
                ]);

                DB::table('store_item_storage')
                    ->join("store_item", "store_item.id", "=", "store_item_storage.store_item_id")
                    ->join("item", "item.id", "=", "store_item.item_id")
                    ->where("store_item_storage.location_id", "=", "4")
                    ->where('item.id', $key)
                    ->decrement('quantity', $value);
            }
            $message = "Transaction successfully processed";
        }
        else {
            $message = "No items selected, transaction not processed";
        }
        
        $items = DB::table('item')
            ->join('store_item', 'store_item.item_id', '=', 'item.id')
            ->where('store_item.store_id', '=', Auth::User()->store_id)
            ->select('item.id', 'item.name', 'store_item.price')
            ->get();

        return view('Sales.sales', compact('items', 'message'));
    }
}
