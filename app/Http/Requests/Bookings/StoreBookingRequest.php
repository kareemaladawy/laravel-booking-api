<?php

namespace App\Http\Requests\Bookings;

use App\Models\Apartment;
use App\Rules\ApartmentAvailableRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('manage-bookings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $apartment = Apartment::findOrFail($this->apartment_id)
            ->only('adult_capacity', 'children_capacity');

        return [
            'apartment_id' => [
                'required',
                'exists:apartments,id',
                new ApartmentAvailableRule()
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'adult_guests' => 'integer|max:' . $apartment['adult_capacity'],
            'children_guests' => 'integer|max:' . $apartment['children_capacity'],
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'apartment_id.exists' => 'Sorry, this apartment is not found',
        ];
    }
}
