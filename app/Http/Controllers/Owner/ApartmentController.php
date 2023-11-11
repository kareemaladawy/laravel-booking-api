<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apartment\StoreApartmentRequest;
use App\Http\Requests\Apartment\UpdateApartmentRequest;
use App\Http\Resources\Apartments\ApartmentDetailsResource;
use App\Models\Apartment;
use App\Models\Property;
use App\Services\ApartmentService;

class ApartmentController extends Controller
{
    public function index(Property $property)
    {
        $apartments = $property->apartments()->withoutGlobalScopes()
            ->with(
                'apartment_type',
                'facilities',
                'prices',
                'bookings',
                'beds.bed_type'
            )->get();

        return ApartmentDetailsResource::collection($apartments);
    }

    public function store(Property $property, StoreApartmentRequest $request)
    {
        $message = ApartmentService::create($property, $request);

        return response()->json([
            'message' => $message
        ], 201);
    }

    public function show(Property $property, Apartment $apartment)
    {
        $apartment->load(
            'apartment_type',
            'facilities',
            'prices',
            'bookings',
            'beds.bed_type'
        );

        return ApartmentDetailsResource::make($apartment);
    }

    public function update(Property $property, Apartment $apartment, UpdateApartmentRequest $request)
    {
        $apartment->update($request->validated());

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    public function deactivate(Property $property, Apartment $apartment)
    {
        $apartment->deactivate();

        return response()->json([
            'message' => 'deactivated.'
        ], 200);
    }

    public function activate(Property $property, Apartment $apartment)
    {
        $apartment->activate();

        return response()->json([
            'message' => 'activated.'
        ], 200);
    }
}
