<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentPrices\StoreApartmentPriceRequest;
use App\Http\Requests\ApartmentPrices\UpdateApartmentPriceRequest;
use App\Http\Resources\Apartments\ApartmentPriceResource;
use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\Property;

class ApartmentPriceController extends Controller
{
    public function index(Property $property, Apartment $apartment)
    {
        return ApartmentPriceResource::collection($apartment->prices);
    }

    public function store(Property $property, Apartment $apartment, StoreApartmentPriceRequest $requset)
    {
        $price = $apartment->prices()->create($requset->validated());

        return response()->json([
            'message' => 'created.'
        ], 201);
    }

    public function show(Property $property, Apartment $apartment, ApartmentPrice $price)
    {
        return ApartmentPriceResource::make($price);
    }

    public function update(Property $property, Apartment $apartment, ApartmentPrice $price, UpdateApartmentPriceRequest $requset)
    {
        $price->update($requset->validated());

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    public function destroy(Property $property, Apartment $apartment, ApartmentPrice $price)
    {
        $price->delete();

        return response()->noContent();
    }
}
