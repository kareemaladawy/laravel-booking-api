<?php

namespace App\Http\Resources\Apartments;

use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentViewResource extends JsonResource
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
            'type' => $this->apartment_type->name,
            'adult_capacity' => $this->adult_capacity,
            'children_capacity' => $this->children_capacity,
            'size' => (int) $this->size,
            'beds_list' => $this->bedslist,
            'bathrooms' => $this->bathrooms,
            'facility_categories' => $this->facility_categories,
            'available_in' => $this->prices->map(function ($price) {
                return [
                    'from' => $price->start_date->toDateString(),
                    'to' => $price->end_date->toDateString(),
                    'price_per_night' => (int) $price->price_per_night
                ];
            }),
            'price' => PricingService::calculateApartmentPriceForDates(
                $this->prices,
                $request->start_date ?? now()->toDateString(),
                $request->end_date ?? now()->addDays(2)->toDateString()
            )
        ];
    }
}
