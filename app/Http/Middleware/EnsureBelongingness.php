<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Belongingness\EnsureApartmentBelongingness;
use App\Http\Middleware\Belongingness\EnsurePriceBelongingness;
use App\Http\Middleware\Belongingness\EnsurePropertyBelongingness;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Symfony\Component\HttpFoundation\Response;

class EnsureBelongingness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Pipeline::send($request)
            ->through([
                EnsurePropertyBelongingness::class,
                EnsureApartmentBelongingness::class
            ])->then(fn () => $next($request));

        return $next($request);
    }
}
