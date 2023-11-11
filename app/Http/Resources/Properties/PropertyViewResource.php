<?php

namespace App\Http\Resources\Properties;

use App\Http\Resources\Apartments\ApartmentViewResource;
use App\Http\Resources\Facilities\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'apartments' => ApartmentViewResource::collection($this->whenLoaded('apartments')),
            'photos' => $this->media->map(fn ($media) => $media->getUrl('thumbnail')),
            'avg_rating' => $this->bookings_avg_rating
        ];
    }
}
