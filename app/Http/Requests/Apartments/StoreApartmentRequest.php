<?php

namespace App\Http\Requests\Apartment;

use App\Models\ApartmentType;
use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('manage-properties');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'apartment_type_id' => 'required|integer|in:' . ApartmentType::pluck('id')->implode(','),
            'name' => 'required|between:5,25',
            'adult_capacity' => 'required|integer|min:0',
            'children_capacity' => 'required|integer|min:0',
            'size' => 'integer|min:0',
            'bathrooms' => 'integer|min:0',
            'facilities' => 'required|array',
            'facilities.*' => 'required|integer|in:' . Facility::pluck('id')->implode(','),
            'rooms' => 'array',
            'rooms.*' => 'array',
            'rooms.*.room_type_id' => 'integer',
            'rooms.*.name' => 'string',
            'rooms.*.beds' => 'array',
            'rooms.*.beds.*' => 'array',
            'rooms.*.beds.*.bed_type_id' => 'integer',
        ];
    }
}
