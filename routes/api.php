<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\ApartmentBookingController;
use App\Http\Controllers\Owner\ApartmentController;
use App\Http\Controllers\Owner\ApartmentPriceController;
use App\Http\Controllers\Public\ApartmentViewController;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\PropertyPhotoController;
use App\Http\Controllers\Public\PropertySearchController;
use App\Http\Controllers\Public\PropertyViewController;
use App\Http\Controllers\User\BookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::as('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', RegisterController::class)
            ->name('register');

        Route::post('login', LoginController::class)
            ->name('login');
    });

Route::as('properties.')->group(function () {
    Route::get('search', PropertySearchController::class)
        ->name('search');

    Route::get('properties/view/{active_property}', PropertyViewController::class)
        ->name('view');
});

Route::get('apartments/view/{active_apartment}', ApartmentViewController::class)
    ->name('apartments.view');


Route::middleware('auth:sanctum')->group(function () {
    Route::as('owner.')
        ->prefix('owner')
        ->group(function () {
            Route::middleware(['ensureBelongingness', 'canManageProperties'])
                ->group(
                    function () {
                        Route::apiResource('properties', PropertyController::class)->parameters([
                            'properties' => 'property'
                        ])->except('destroy');

                        Route::prefix('properties/{property}')
                            ->group(function () {
                                Route::as('properties.')->group(function () {
                                    Route::as('operations.')->group(function () {
                                        Route::put(
                                            'activate',
                                            [PropertyController::class, 'activate']
                                        )->name('activate');

                                        Route::put(
                                            'deactivate',
                                            [PropertyController::class, 'deactivate']
                                        )->name('deactivate');
                                    });
                                });

                                Route::post('photos', [PropertyPhotoController::class, 'store'])
                                    ->name('properties.photo.store');

                                Route::put(
                                    'photos/{photo}/reorder',
                                    [PropertyPhotoController::class, 'reorder']
                                )->middleware('ensurePhotoBelongingness')
                                    ->name('properties.photo.reorder');

                                Route::apiResource('apartments', ApartmentController::class)
                                    ->except('destroy');

                                Route::as('apartment.')->prefix('apartments')->group(function () {
                                    Route::as('operations.')->group(function () {
                                        Route::put(
                                            '{apartment}/activate',
                                            [ApartmentController::class, 'activate']
                                        )->name('activate');

                                        Route::put(
                                            '{apartment}/deactivate',
                                            [ApartmentController::class, 'deactivate']
                                        )->name('deactivate');
                                    });

                                    Route::get(
                                        '{apartment}/bookings',
                                        ApartmentBookingController::class
                                    )->name('bookings');
                                });


                                Route::apiResource(
                                    'apartments.prices',
                                    ApartmentPriceController::class
                                )->middleware('ensurePriecBelongingness');
                            });
                    }
                );
        });

    Route::as('user.')
        ->prefix('user')
        ->middleware('canManageBookings', 'ensureBookingBelongingness')
        ->group(function () {
            Route::apiResource('bookings', BookingController::class)
                ->except('destroy')
                ->withTrashed(['index', 'show']);

            Route::put('bookings/{booking}/cancel', [BookingController::class, 'cancel'])
                ->name('bookings.cancel');
        });
});
