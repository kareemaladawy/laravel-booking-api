<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Properties\PropertyViewResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyViewController extends Controller
{
    public function __invoke(Property $property)
    {
        $property->load(['apartments' => function ($query) {
            $query->whereHas('prices');
        }]);

        return PropertyViewResource::make($property);
    }
}
