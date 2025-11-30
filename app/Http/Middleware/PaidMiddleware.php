<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaidMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && auth()->user()->getCurrentActiveSubscription()?->isFree()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
