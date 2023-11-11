<?php

namespace App\Http\Resources\Bookings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class BookingResource extends JsonResource
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
            'apartment_name' => $this->apartment->name,
            'apartment_view_link' => route('apartments.view', ['active_apartment' => $this->apartment]),
            'start_date' => $this->start_date->toDateString(),
            'end_date' => $this->end_date->toDateString(),
            'adult_guests' => $this->whenNotNull($this->adult_guests),
            'children_guests' => $this->whenNotNull($this->children_guests),
            'total_price' => $this->total_price,
            'rating' => $this->whenNotNull($this->rating),
            'review_comment' => $this->whenNotNull($this->review_comment),
            'booked_at' => $this->when(Gate::allows('manage-properties'), $this->created_at->toDateString()),
            'cancelled_at' => $this->whenNotNull($this->deleted_at?->toDateString())
        ];
    }
}
