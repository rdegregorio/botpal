<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ComingSoonMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            return $next($request);
        }

        if (!config('app.coming_soon', false)) {
            return $next($request);
        }

        $username = config('app.coming_soon_user', 'admin');
        $password = config('app.coming_soon_password', 'secret');

        if ($request->getUser() !== $username || $request->getPassword() !== $password) {
            return response('Unauthorized', 401, [
                'WWW-Authenticate' => 'Basic realm="Coming Soon"'
            ]);
        }

        return $next($request);
    }
}
