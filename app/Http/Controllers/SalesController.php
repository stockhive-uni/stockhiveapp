<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        $items = DB::table('transaction')
            ->join('users', 'transaction.user_id', '=', 'users.id')
            ->where('transaction.store_id', Auth::User()->store_id)
            ->select('transaction.id', 'users.first_name', 'transaction.date_time')
            ->get();
        return view('Sales.index', ['items' => $items]);
    }

    public function startSale() {
        return view('Sales.sales');
    }

    public function viewDetails() {

    }

    public function generateInvoice() {
        
    }
}
