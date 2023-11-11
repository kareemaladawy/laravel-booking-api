<?php

namespace App\Http\Middleware\Belongingness;

use Closure;
use Illuminate\Http\Request;

class EnsureBookingBelongingness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('booking')) {
            if ($request->user()->id != $request->route('booking')->user_id) {
                abort(403, 'booking does not belong to you.');
            }
        }

        return $next($request);
    }
}
