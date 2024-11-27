<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;

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

    public function viewDetails(Request $request) {
        $transaction = Transaction::where('id', $request->id)->get();
        return view('Sales.transaction-details', ['transaction' => $transaction]);
    }

    public function generateInvoice(Request $request) {
        $id = $request->id;
        return view('Sales.invoice', ['id' => $id]);
    }
}
