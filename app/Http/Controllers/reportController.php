<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class reportController extends Controller
{
        
    //redirect to report page
    public function index(Request $request) 
    {
        //here we use chart.js with the id of the item and orders to make a chart of order history

        
        return view('StockManager.report', ['request' => $request]);
    } 
}
