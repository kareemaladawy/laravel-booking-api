<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Properties\PropertyViewResource;
use App\Models\Facility;
use App\Models\Geoobject;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertySearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $propertiesQuery = Property::withWhereHas('apartments.prices', function ($query) use ($request) {
            $query->isValidForRange(range: [
                $request->start_date ?? now()->addDay()->toDateString(),
                $request->end_date ?? now()->addDays(5)->toDateString(),
            ]);
        })
            ->with([
                'city:id,name',
                'apartments.apartment_type:id,name',
                'apartments.beds.bed_type:id,name',
                'media' => fn ($query) => $query->orderBy('order_column')
            ])
            ->when($request->price_from && $request->price_to, function ($query) use ($request) {
                $query->whereHas('apartments.prices', function ($query) use ($request) {
                    $query->where('price_per_night', '>=', $request->price_from)
                        ->where('price_per_night', '<=', $request->price_to);
                });
            })
            ->when($request->price_from, function ($query) use ($request) {
                $query->whereHas('apartments.prices', function ($query) use ($request) {
                    $query->where('price_per_night', '>=', $request->price_from);
                });
            })
            ->when($request->price_to, function ($query) use ($request) {
                $query->whereHas('apartments.prices', function ($query) use ($request) {
                    $query->where('price_per_night', '<=', $request->price_to);
                });
            })
            ->when($request->city_id, function ($query) use ($request) {
                $query->where('city_id', $request->city_id);
            })
            ->when($request->country_id, function ($query) use ($request) {
                $query->whereHas('city', fn ($q) => $q->where('country_id', $request->country_id));
            })
            ->when($request->geoobject_id, function ($query) use ($request) {
                $geoobject = Geoobject::find($request->geoobject_id);
                if ($geoobject) {
                    $condition = "(2 * 6371
                    * asin(sqrt(
                        pow(sin((radians(`lat`) - radians($geoobject->lat)) / 2), 2)
                        + cos(radians($geoobject->lat))
                        * cos(radians(`lat`))
                        * pow(sin((radians(`long`) - radians($geoobject->long)) / 2), 2)
                    ))) < 10";
                    $query->whereRaw($condition);
                }
            })
            ->when(
                $request->adult_capacity && $request->children_capacity,
                function ($query) use ($request) {
                    $query->withWhereHas('apartments', function ($query) use ($request) {
                        $query->where('adult_capacity', '>=', $request->adult_capacity)
                            ->where('children_capacity', '>=', $request->children_capacity)
                            ->orderBy('adult_capacity')
                            ->orderBy('children_capacity')
                            ->limit(1);
                    });
                }
            )
            ->when($request->facilities, function ($query) use ($request) {
                $query->whereHas('facilities', function ($query) use ($request) {
                    $query->whereIn('facilities.id', $request->facilities);
                });
            });

        $facilities = Facility::query()
            ->withCount(['properties' => function ($property) use ($propertiesQuery) {
                $property->whereIn('property_id', $propertiesQuery->pluck('id'));
            }])
            ->get()
            ->where('properties_count', '>', 0)
            ->sortByDesc('properties_count')
            ->pluck('properties_count', 'name');

        $properties = $propertiesQuery
            ->orderBy('bookings_avg_rating', 'desc')
            ->paginate(10)
            ->withQueryString();

        return [
            'properties' => PropertyViewResource::collection($properties)
                ->response()
                ->getData(true),
            'facilities' => $facilities,
        ];
    }
}
