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

    public function downloadInvoice(Request $request) {
        // https://bagisto.com/en/how-to-generate-a-pdf-in-laravel-view/

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
}
