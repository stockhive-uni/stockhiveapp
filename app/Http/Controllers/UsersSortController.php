<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;  

class UsersSortController extends Controller {
    public function sort(Request $request) {
        $sort = $request->input('sort', 'id'); // Default to ID
        $page = $request->input('page', 1); // Default to page 1 if no page is set
        $employees = Employee::with('store') // Stackoverflow answer for sorting using Laravel https://stackoverflow.com/a/60182632
                    ->orderBy($sort) 
                    ->paginate(5) // Pagination documentation https://laravel.com/docs/11.x/pagination
                    ->onEachSide(1); // Shrinking the amount of pages shown on the pages bar: https://laracasts.com/discuss/channels/general-discussion/limit-the-pagination-link-amount?page=1&replyId=744325 reply by "2108web"
        return view('Admin.index', ['employees' => $employees], ['page' => $page]);
    }
}