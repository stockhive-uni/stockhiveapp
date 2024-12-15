<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class allowAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //gets order id from url
        $orderIdFromURL = $request->input('order');

        //compare to database
        $storeIdFromOrder = Order::where('id', '=', $orderIdFromURL)->value('store_id');

        if (Auth::user()->store_id != $storeIdFromOrder) {
            return redirect()->route('dashboard');
        }        

        return $next($request);
    }
}
