<?php

namespace App\Observers;

use App\Models\Property;
use GuzzleHttp\Client;

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     */
    public function creating(Property $property): void
    {
        if (auth()->check()) {
            $property->owner_id = auth()->id();
        }

        if (is_null($property->lat) && is_null($property->long) && !(app()->environment('testing'))) {
            $fullAddress = urlencode(
                $property->address_street . ', '
                    . $property->address_postcode . ', '
                    . $property->city->name . ', '
                    . $property->city->country->name
            );

            $client = new Client();
            $response = $client->get('https://api.tomtom.com/search/2/geocode/%27.' . $fullAddress . '.%27.json', [
                'query' => [
                    'key' => config('tomtom.API_KEY'),
                    'limit' => 1
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (!empty($result)) {
                $property->lat = $result['results'][0]['position']['lat'] ?? '';
                $property->long = $result['results'][0]['position']['lon'] ?? '';
            }
        }
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "restored" event.
     */
    public function restored(Property $property): void
    {
        //
    }

    /**
     * Handle the Property "force deleted" event.
     */
    public function forceDeleted(Property $property): void
    {
        //
    }
}
