<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\User;
use Closure;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = $request->user();

        if ( ! $user) {
            return false;
        }

        if($user->stripe_id && $request->routeIs('dashboard')) {
            return $next($request);
        }

        $s = $user->getCurrentActiveSubscription();

        if (! $s) {
            return redirect(route('pages.subscribe'));
        }

        return $next($request);
    }
}
