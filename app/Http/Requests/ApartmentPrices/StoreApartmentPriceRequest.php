<?php

namespace App\Http\Requests\ApartmentPrices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreApartmentPriceRequest extends FormRequest
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
            'start_date' => [
                'required', 'date', 'after:today',
                Rule::unique('apartment_prices')->where(function ($query) {
                    return $query->where('apartment_id', $this->route('apartment')->id)
                        ->where('start_date', $this->start_date);
                })
            ],
            'end_date' => [
                'required', 'date', 'after:start_date',
                Rule::unique('apartment_prices')->where(function ($query) {
                    return $query->where('apartment_id', $this->route('apartment')->id)
                        ->where('end_date', $this->end_date);
                })
            ],
            'price_per_night' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'start_date.unique' => 'start date for selected apartment date range already exists.',
            'end_date.unique' => 'end date for selected apartment date range already exists.',
        ];
    }
}
