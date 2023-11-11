<?php

namespace App\Services;

use App\Http\Requests\Apartment\StoreApartmentRequest;
use App\Models\Property;

class ApartmentService
{
    public static function create(Property $property, StoreApartmentRequest $request)
    {
        $apartment = $property->apartments()->create($request->validated());

        if ($request->has('facilities')) {
            $apartment->facilities()->sync($request->facilities);
        }

        if ($request->has('rooms')) {
            foreach ($request->rooms as $room) {
                $new_room = $apartment->rooms()->create($room);

                if ($room['beds']) {
                    foreach ($room['beds'] as $bed) {
                        $new_room->beds()->create($bed);
                    }
                }
            }
        }

        return 'created.';
    }
}
