<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StockSortController extends Controller {
    public function sort(Request $request) {
        $sort = $request->input('sort', 'id'); // Default to ID
        $page = $request->input('page', 1); // Default to page 1 if no page is set
        $items = Item::with('department') // Stackoverflow answer for sorting using Laravel https://stackoverflow.com/a/60182632
                    ->orderBy($sort) 
                    ->paginate(5) // Pagination documentation https://laravel.com/docs/11.x/pagination
                    ->onEachSide(1); // Shrinking the amount of pages shown on the pages bar: https://laracasts.com/discuss/channels/general-discussion/limit-the-pagination-link-amount?page=1&replyId=744325 reply by "2108web"
        return view('StockManager.index', ['items' => $items], ['page' => $page]);
    }

    public function sortOrder(Request $request) {
        $sort = $request->input('sort', 'id'); // Default to ID
        $page = $request->input('page', 1); // Default to page 1 if no page is set
        $count = $request->input('count');
        $deliveryDate = $request->input('deliveryDate');
        $itemsPrice = $request->input('itemsPrice');

        $ids = explode(",", $request->items);
        $allItems = $ids;
        
        $items = Item::whereIn('id', $ids)
            ->orderBy($sort)
            ->paginate(3)
            ->onEachSide(1);

        return view('StockManager.order', ['items' => $items, 'allItems' => $allItems, 'page' => $page, 'count' => $count, 'deliveryDate' => $deliveryDate, 'totalPrice' => $itemsPrice]);
    }
}