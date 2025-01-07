<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\store_item;
use App\Models\store_item_storage;
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
        ->where('store_item.store_id', '=', Auth::user()->store_id)
        ->select('item.name AS itemName', 'item.price', 'item.id', 'department.name AS departmentName','store_item.low-stock-amount AS lowStockNum','store_item_storage.quantity')
        ->get();


        //spot check for items query needed here
        $spotCheckItemWarning = store_item_storage::with(['store_item', 'store_item.store', 'store_item.item'])
        ->join('store_item', 'store_item.id', '=', 'store_item_storage.store_item_id')
        ->join('store', 'store_item.store_id', '=', 'store_id')
        ->join('item', 'store_item.item_id', '=', 'item.id')
        ->join('department', 'item.department_id', '=', 'department.id')
        ->join('location', 'location.id', '=', 'store_item_storage.location_id')
        ->whereRelation('store_item.store', 'store_id', '=', Auth::user()->store_id) //from the documentation: https://laravel.com/docs/11.x/eloquent-relationships#inline-relationship-existence-queries
        ->where('store_item.store_id', '=', Auth::user()->store_id)
        ->OrderBy('last_spot_checked', 'asc')
        ->select('item.name AS itemName', 'item.price', 'item.id', 'department.name AS departmentName', 'store_item_storage.quantity', 'location.name AS location', 'store_item.last_spot_checked as last_spot_checked')
        ->limit(3)
        ->distinct()
        ->get();

        //gets items that arent in the store_item_storage table, showing that there is no stock of it.
        $noStockWarning = store_item::with(['store', 'item', 'item.department'])
        ->leftJoin('store_item_storage', 'store_item.id', '=','store_item_storage.store_item_id') //selects records that have matches with the store_item_storage table
        ->whereNull('store_item_storage.store_item_id') //learnt how to use whereNull here https://laravel.com/docs/11.x/collections#method-wherenull
        ->limit(3) //reverses the selection, so only selects records that have no matches.
        ->get(); 
        return view('Inventory.index',['lowStockItemWarning' => $lowStockItemWarning, 'spotCheckItemWarning' => $spotCheckItemWarning, 'noStockWarning' => $noStockWarning]);
    }



    public function spotCheck(Request $request) {
        //goes to spot check page where info is put into it, and then a post request is made to come back here and update the table, then redirect back to the inventory home page.
        $spotCheckItem = store_item::with(['store', 'item', 'item.department'])
        ->where('store_id', '=', Auth::user()->store_id)
        ->where('id', '=', $request->input('spotcheck'))
        ->OrderBy('last_spot_checked', 'asc')
        ->limit(3)
        ->get();
        //updating time on 
        
        
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

        DB::table('store_item_storage')->where('store_item_id', '=', $request->input('stockID'))->update(['quantity' => $request->input('SpotCheckNum')]);

        return redirect()->route('inventory');
    }

    public function AddToFloor() {
        //here we load the inventory specifically with location id in storage

        $inventoryFromStorage = DB::table('store_item_storage')
        ->join('store_item', 'store_item.id', '=', 'store_item_storage.store_item_id')
        ->join('item', 'store_item.item_id', '=' , 'item.id')
        ->join('department', 'item.department_id', '=', 'department.id')
        ->join('location', 'store_item_storage.location_id', '=', 'location.id')
        ->where('store_item.store_id', '=', Auth::user()->store_id)
        ->where('location.name', '=', 'Warehouse')
        ->select('item.name AS itemName', 'department.name AS departmentName', 'location.name AS locationName', 'store_item.*', 'store_item_storage.quantity', 'store_item_storage.store_item_id AS IdOfItem')
        ->get();


        return view('Inventory.add', ['inventoryFromStorage' => $inventoryFromStorage]);
    }

    public function updateInventory(Request $request) {

        //get items
        $checkbox = $request->input('checkbox');
        $maxQty = $request->input('maxQty');
        $toAdd = $request->input('ItemQtyAdd');

        foreach ($checkbox as $item) {
            if ($checkbox[$item] == $maxQty[$item]) {
                //delete current record
                store_item_storage::where('store_item_id', '=', $item)->where('location_id', '=', 3)->delete();
            }
            else {
                //if different amounts
                store_item_storage::where('store_item_id', '=', $item)->where('location_id', '=', 3)->update(['quantity' => $maxQty[$item] - $toAdd[$item]]);
            }

                //checks record exists
                $updated = store_item_storage::firstOrCreate(['store_item_id' => $item, 'location_id' => 4], ['quantity' => 0]);
                
                //updates the storage query with correct quantity
                $updated->increment('quantity', $toAdd[$item]);
       
                   store_item_storage::where('quantity', '=', 0)->delete();
        }
        return redirect()->route('inventory');
    }

    public function remove() {

        $InventoryFromFloor = DB::table('store_item_storage')
        ->join('store_item', 'store_item.id', '=', 'store_item_storage.store_item_id')
        ->join('item', 'store_item.item_id', '=' , 'item.id')
        ->join('department', 'item.department_id', '=', 'department.id')
        ->join('location', 'store_item_storage.location_id', '=', 'location.id')
        ->where('store_item.store_id', '=', Auth::user()->store_id)
        ->where('location.name', '=', 'Floor')
        ->select('item.name AS itemName', 'department.name AS departmentName', 'location.name AS locationName', 'store_item.*', 'store_item_storage.quantity', 'store_item_storage.store_item_id AS IdOfItem')
        ->get();

    
        return view('Inventory.remove', ['InventoryFromFloor' => $InventoryFromFloor]);
    }

    public function removeFromFloor(Request $request) {
        //get items
        $checkbox = $request->input('checkbox');
        $QtyOnFloor = $request->input('QtyOnFloor');
        $toRemove = $request->input('ItemQtyRemove');

        if (isset($checkbox)) {

            foreach ($checkbox as $item) {
                
                if ($checkbox[$item] == $toRemove[$item]) {
                    //delete floor record if it is the full amount
                    store_item_storage::where('store_item_id', '=', $item)->where('location_id', '=', 4)->delete();
                }
                else {
                    store_item_storage::where('store_item_id', '=', $item)->where('location_id', '=', 4)->update(['quantity' => $QtyOnFloor[$item] - $toRemove[$item]]);
                }

                //checks record exists
                $updated = store_item_storage::firstOrCreate(['store_item_id' => $item, 'location_id' => 3], ['quantity' => 0]); //discovered this way of checking if a record exists or not, if it doesnt it still creates it: https://laravel.com/docs/11.x/eloquent#retrieving-or-creating-models
                
                //updates the storage query with correct quantity
                $updated->increment('quantity', $toRemove[$item]);

                store_item_storage::where('quantity', '=', 0)->delete();
            }
            return redirect()->route('inventory');
        }
    }
}
