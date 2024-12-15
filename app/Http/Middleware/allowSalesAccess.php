<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class allowSalesAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                //gets order id from url
                $salesIdFromURL = $request->input('id');

                //compare to database
                $storeIdFromOrder = Transaction::where('id', '=', $salesIdFromURL)->value('store_id');
        
                if (Auth::user()->store_id != $storeIdFromOrder) {
                    return redirect()->route('dashboard');
                }        
        
                return $next($request);

        return $next($request);
    }
}
