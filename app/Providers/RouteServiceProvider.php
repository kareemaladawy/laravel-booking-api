<?php

namespace App\Providers;

use App\Models\Apartment;
use App\Models\Property;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Route::bind('apartment', function ($id) {
            return Apartment::withoutGlobalScopes()->findOrFail($id);
        });

        Route::bind('active_apartment', function ($id) {
            return Apartment::findOrFail($id);
        });

        Route::bind('property', function ($id) {
            return Property::withoutGlobalScopes()->findOrFail($id);
        });

        Route::bind('active_property', function ($id) {
            return Property::findOrFail($id);
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
