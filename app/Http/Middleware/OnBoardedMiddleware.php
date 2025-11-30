<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnBoardedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->user()?->chatConfigLatest) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
