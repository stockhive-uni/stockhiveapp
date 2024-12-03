<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\store_item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        //get stock item from database that is lower than the low stock number.
        $lowStockItemWarning = DB::table('store_item_storage')
        ->join('store_item', 'store_item.id' , '=', 'store_item_storage.store_item_id')
        ->join('store', 'store_item.store_id', '=', 'store.id')
        ->join('item', 'store_item.item_id', '=' , 'item.id')
        ->join('department', 'item.department_id', '=','department.id')
        ->whereColumn('store_item_storage.quantity','<=','store_item.low-stock-amount') //found that using standard where does not work as that is only for comparing a field to something you entered, use whereColumn to compare to columns. Found here: https://laravel-code.tips/you-can-use-eloquent-wherecolumn-to-compare-columns-to-each-other/
        ->select('item.name AS itemName', 'item.price', 'department.name AS departmentName','store_item.low-stock-amount AS lowStockNum','store_item_storage.quantity')
        ->get();

        //spot check for items query needed here
        $spotCheckItemWarning = store_item::with(['store', 'item'])
        ->where('store_id', '=', Auth::user()->store_id)
        ->OrderBy('last_spot_checked', 'asc')
        ->limit(5)
        ->get();


        return view('Inventory.index',['lowStockItemWarning' => $lowStockItemWarning, 'spotCheckItemWarning' => $spotCheckItemWarning]);
    }

    public function spotCheck(Request $request) {
        //goes to spot check page where info is put into it, and then a post request is made to come back here and update the table, then redirect back to the inventory home page.
        $spotCheckItem = store_item::with(['store', 'item', 'item.department'])
        ->where('store_id', '=', Auth::user()->store_id)
        ->where('id', '=', $request->input('spotcheck'))
        ->OrderBy('last_spot_checked', 'asc')
        ->limit(5)
        ->get();
        //updating time on spotcheck
        
        //redirect if invalid URL
        if ($request->input('spotcheck') == null) {
            return redirect()->route('inventory');
        }

        return view('Inventory.spot-check', ['spotCheckItem' => $spotCheckItem]);
    }

    public function confirmCheck(Request $request) {
        //here we update the store_item time to the current time so it stays away from the top of the list, also update the stock count currently available in store
        
        //update last_spot_checked
        $query = store_item::where('id', '=', $request->input('stockID'))
        ->update(['last_spot_checked' => now()]);

        //update quantity from spot check

        //maybe make it so it checks if the row exists, if it doesnt it creates it with all the data?
        DB::table('store_item_storage')->where(['quantity' => $request->input('SpotCheckNum')]);
    }
}
