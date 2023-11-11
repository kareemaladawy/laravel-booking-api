<?php

namespace App\Services;

use App\Models\Property;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PropertyPhotoService
{
    public static function reorder(Property $property, Media $photo, int $new_order)
    {
        $query = $property->media();

        if ($new_order < $photo->order_column) {
            $query
                ->whereBetween('order_column', [$new_order, $photo->order_column - 1])
                ->increment('order_column');
        } else {
            $query
                ->whereBetween('order_column', [$photo->order_column + 1, $new_order])
                ->decrement('order_column');
        }

        $photo->order_column = $new_order;
        $photo->save();

        return $photo;
    }
}
