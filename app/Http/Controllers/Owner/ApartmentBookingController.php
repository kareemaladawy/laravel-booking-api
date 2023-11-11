<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bookings\BookingResource;
use App\Models\Apartment;
use App\Models\Property;

class ApartmentBookingController extends Controller
{
    public function __invoke(Property $property, Apartment $apartment)
    {
        $bookings = $apartment->bookings()->withTrashed()->get();

        return BookingResource::collection($bookings);
    }
}
