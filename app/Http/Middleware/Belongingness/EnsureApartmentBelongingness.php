<?php

namespace App\Http\Middleware\Belongingness;

use Closure;
use Illuminate\Http\Request;

class EnsureApartmentBelongingness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __invoke(Request $request, Closure $next)
    {
        if ($request->route('apartment')) {
            if ($request->route('property')->id != $request->route('apartment')->property_id) {
                abort(403, 'apartment does not belong to property.');
            }
        }

        return $next;
    }
}
