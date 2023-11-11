<?php

namespace App\Http\Middleware\Belongingness;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePriceBelongingness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __invoke(Request $request, Closure $next): Response
    {
        if ($request->route('price')) {
            if ($request->route('price')->apartment_id != $request->route('apartment')->id) {
                abort(403, 'price does not belong to apartment.');
            }
        }

        return $next($request);
    }
}
