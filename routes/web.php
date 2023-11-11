<?php

use App\Models\Geoobject;
use App\Models\Property;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $geoobject = Geoobject::first();
    if ($geoobject) {
        $condition = "(
            6371 * acos(
                cos(radians(" . $geoobject->lat . "))
                * cos(radians(`lat`))
                * cos(radians(`long`) - radians(" . $geoobject->long . "))
                + sin(radians(" . $geoobject->lat . ")) * sin(radians(`lat`))
            ) < 10
        )";

        $properties = Property::whereRaw($condition)->get();

        dd($properties);
    }
});
