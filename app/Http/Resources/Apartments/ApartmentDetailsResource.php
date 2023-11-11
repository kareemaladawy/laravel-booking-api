<?php

namespace App\Http\Resources\Apartments;

use App\Http\Resources\Facilities\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'active' => $this->active ? 'yes' : 'no',
            'type' => $this->apartment_type->name,
            'adult_capacity' => $this->adult_capacity,
            'children_capacity' => $this->children_capacity,
            'size' => $this->size,
            'beds_list' => $this->beds_list,
            'bathrooms' => $this->bathrooms,
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'available_in' => $this->prices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'from' => $price->start_date->toDateString(),
                    'to' => $price->end_date->toDateString(),
                    'price_per_night' => $price->price_per_night
                ];
            }),
            'booked_in' => $this->bookings->map(function ($booking) {
                return [
                    'from' => $booking->start_date->toDateString(),
                    'to' => $booking->end_date->toDateString(),
                    'total_price' => $booking->total_price
                ];
            }),
        ];
    }
}
