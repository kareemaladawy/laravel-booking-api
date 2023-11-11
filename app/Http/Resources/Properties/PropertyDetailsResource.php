<?php

namespace App\Http\Resources\Properties;

use App\Http\Resources\Apartments\ApartmentDetailsResource;
use App\Http\Resources\Facilities\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyDetailsResource extends JsonResource
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
            'city' => $this->city->name,
            'active' => $this->active ? 'yes' : 'no',
            'address' => $this->address,
            'bookings_avg_rating' => $this->bookings_avg_rating ?? 'no average rating yet',
            'apartments' => ApartmentDetailsResource::collection($this->whenLoaded('apartments')),
            'apartments_view_link' => route('owner.apartments.index', ['property' => $this]),
            'facilities' => FacilityResource::collection($this->facilities),
            'photos' => PropertyPhotoResource::collection($this->getMedia('photos'))
        ];
    }
}
