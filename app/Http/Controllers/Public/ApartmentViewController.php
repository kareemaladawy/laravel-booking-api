<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentDetailsResource;
use App\Http\Resources\Apartments\ApartmentViewResource;
use App\Models\Apartment;

class ApartmentViewController extends Controller
{
    public function __invoke(Apartment $apartment)
    {
        $apartment->load(
            'facilities.category',
            'prices',
            'bookings:id,apartment_id,start_date,end_date'
        );

        $apartment->setAttribute(
            'facility_categories',
            $apartment->facilities
                ->groupBy('category.name')
                ->mapWithKeys(fn ($items, $key) => [$key => $items->pluck('name')])
        );

        return ApartmentViewResource::make($apartment);
    }
}
