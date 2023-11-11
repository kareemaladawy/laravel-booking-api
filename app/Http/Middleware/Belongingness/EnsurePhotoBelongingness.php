<?php

namespace App\Http\Middleware\Belongingness;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhotoBelongingness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('photo')) {
            if ($request->route('photo')->model_id != $request->route('property')->id) {
                abort(403, 'photo does not belong to property');
            }
        }

        return $next($request);
    }
}
