<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //when loading into the stockManager view, items are displayed with their department
        $items = Item::with('department')
            ->paginate(5)
            ->onEachSide(1);
        return view('StockManager.index', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function chosenItems(Request $request) 
    {
        if ($request->has("Order")) {
            $items = DB::table('item')
            ->join('store_item', 'store_item.item_id', '=', 'item.id')
            ->where('store_item.store_id', '=', Auth::User()->store_id)
            ->select('item.id', 'item.name', 'store_item.price')
            ->get();

            return view('StockManager.order', compact('items'));
        }
        else {
            if ($request->items != null) {
                $stock = null;
                if ($request->has("Report")) {
                    $stock = Item::whereIn('id', $request->items)->with('department')->get();
                }
                else {
                    $stock = Item::where('id', $request->item)->with('department')->get();
                }
                $allresults = array();
                // Loop through all items.
                foreach ($stock as $item) {
                    $id=$item->id;
                    $query = DB::table('Transaction_Item') // Use laravel's query builder. https://www.google.com/search?client=firefox-b-d&q=laravel+query+builder
                        ->join('Transaction', 'Transaction_Item.transaction_id', '=', 'Transaction.id')
                        ->where('Transaction_Item.item_id', $id)
                        ->where('store_id', '=', Auth::user()->store_id)
                        ->select('Transaction_Item.quantity', 'Transaction_Item.price', 'Transaction.date_time')
                        ->get();
    
                    if ($query->isNotEmpty()) {
                        $data = [];
                        // Iterate through transactions.
                        foreach ($query as $transaction) {
                            $quantity = $transaction->quantity;
                            $price = $transaction->price;
                            $date = $transaction->date_time;
                            // Get the month from date
                            $month = (int) date('m', strtotime($date)); // Getting date using strtotime: https://www.php.net/manual/en/function.strtotime.php
                            // Store the data in an array, group by month.
                            if (!isset($data[$month])) {
                                $data[$month] = [
                                    'total' => 0, 
                                    'month' => $month,
                                ];
                            }
                            // Set the total for the month.
                            $data[$month]['total'] += $quantity * $price;
                        }
                         // Add the item's monthly data
                        $allresults[] = [
                            'item_name' => $item->name,  // Item name
                            'data' => $data, // Item information per month.
                        ];
                    }
                    else {
                        $data = [];
                        $data[0] = [
                            'total' => 0,
                            'month' => 0,
                        ];
                        $allresults[] = [
                            'item_name' => $item->name,
                            'data' => $data,
                        ];
                    }
                } 
                return view('StockManager.report', ['allresults' => $allresults, 'items' => $request->items]);
            }
            else {
                $items = Item::with('department')
                    ->paginate(5)
                    ->onEachSide(1);
                return view('StockManager.index', ['items' => $items, 'error' => 'No Products Selected']);
            }
        }
    }

    public function downloadReport(Request $request) {
        // Used knowledge gained from downloading the invoice - Rob
        $stock = null;
        $stock = Item::whereIn('id', $request->items)->with('department')->get();
        $allresults = array();
        // Loop through all items.
        foreach ($stock as $item) {
            $id=$item->id;
            $query = DB::table('Transaction_Item') // Use laravel's query builder. https://www.google.com/search?client=firefox-b-d&q=laravel+query+builder
                ->join('Transaction', 'Transaction_Item.transaction_id', '=', 'Transaction.id')
                ->where('Transaction_Item.item_id', $id)
                ->where('store_id', '=', Auth::user()->store_id)
                ->select('Transaction_Item.quantity', 'Transaction_Item.price', 'Transaction.date_time')
                ->get();

            if ($query->isNotEmpty()) {
                $data = [];
                // Iterate through transactions.
                foreach ($query as $transaction) {
                    $quantity = $transaction->quantity;
                    $price = $transaction->price;
                    $date = $transaction->date_time;
                    // Get the month from date
                    $month = (int) date('m', strtotime($date)); // Getting date using strtotime: https://www.php.net/manual/en/function.strtotime.php
                    // Store the data in an array, group by month.
                    if (!isset($data[$month])) {
                        $data[$month] = [
                            'total' => 0, 
                            'month' => $month,
                        ];
                    }
                    // Set the total for the month.
                    $data[$month]['total'] += $quantity * $price;
                }
                // Add the item's monthly data
                $allresults[] = [
                    'item_name' => $item->name,  // Item name
                    'data' => $data, // Item information per month.
                ];
            }
            else {
                $data = [];
                $data[0] = [
                    'total' => 0,
                    'month' => 0,
                ];
                $allresults[] = [
                    'item_name' => $item->name,
                    'data' => $data,
                ];
            }
        }

        $name = Auth::User()->first_name . " " . Auth::User()->last_name;

        $pdf = Pdf::loadView('StockManager.download', compact('allresults', 'name'));

        // Stream the PDF to the browser for download
        return $pdf->download('report.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
