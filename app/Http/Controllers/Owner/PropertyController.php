<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\StorePropertyRequest;
use App\Http\Requests\Properties\UpdatePropertyRequest;
use App\Http\Resources\Properties\PropertyDetailsResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = $request->user()->properties()->withoutGlobalScopes()
            ->with(
                'city:id,name',
                'facilities',
                'bookings'
            )->get();

        return PropertyDetailsResource::collection($properties);
    }

    public function store(StorePropertyRequest $request)
    {
        Property::create($request->validated());

        return response()->json([
            'message' => 'created.'
        ], 201);
    }

    public function update(Property $property, UpdatePropertyRequest $request)
    {
        $property->update($request->validated());

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    public function show(Property $property)
    {
        return PropertyDetailsResource::make($property);
    }

    public function deactivate(Property $property)
    {
        $property->deactivate();

        return response()->json([
            'message' => 'deactivated.'
        ], 200);
    }

    public function activate(Property $property)
    {
        $property->activate();

        return response()->json([
            'message' => 'activated.'
        ], 200);
    }
}
