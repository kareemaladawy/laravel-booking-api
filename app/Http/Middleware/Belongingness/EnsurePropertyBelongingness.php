<?php

namespace App\Http\Middleware\Belongingness;

use Closure;
use Illuminate\Http\Request;

class EnsurePropertyBelongingness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __invoke(Request $request, Closure $next)
    {
        if ($request->route('property')) {
            if ($request->user()->id != $request->route('property')->owner_id) {
                abort(403, 'property does not belong to you.');
            }
        }

        return $next;
    }
}
