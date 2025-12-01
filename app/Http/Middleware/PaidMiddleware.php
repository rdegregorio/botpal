<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaidMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // For now, allow all users with any subscription (Stripe not yet configured)
        // When Stripe is ready, uncomment the isFree() check below
        // if(auth()->check() && auth()->user()->getCurrentActiveSubscription()?->isFree()) {
        //     return redirect()->route('dashboard');
        // }

        return $next($request);
    }
}
