<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\ReorderPropertyPhotoRequest;
use App\Http\Requests\Properties\StorePropertyPhotoRequest;
use App\Http\Resources\Properties\PropertyPhotoResource;
use App\Models\Property;
use App\Services\PropertyPhotoService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PropertyPhotoController extends Controller
{
    public function store(Property $property, StorePropertyPhotoRequest $request)
    {
        foreach ($request->file('photos') as $photo) {
            $property->addMedia($photo)->toMediaCollection('photos');
        }

        return response()->json(
            PropertyPhotoResource::collection($property->getMedia('photos')),
            201
        );
    }

    public function reorder(Property $property, Media $photo, ReorderPropertyPhotoRequest $request)
    {
        $reordered_photo = PropertyPhotoService::reorder(
            $property,
            $photo,
            $request->new_order
        );

        return response()->json([
            'new_order' => $reordered_photo->order_column
        ], 200);
    }
}
