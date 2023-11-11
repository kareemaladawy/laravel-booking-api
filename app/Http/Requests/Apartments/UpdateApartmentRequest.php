<?php

namespace App\Http\Requests\Apartment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateApartmentRequest extends FormRequest
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
            'apartment_type_id' => 'integer|exists:apartment_types,id',
            'name' => 'between:7,50',
            'adult_capacity' => 'integer|min:0',
            'children_capacity' => 'integer|min:0',
            'size' => 'min:0',
            'bathrooms' => 'min:0',
            'active' => 'boolean'
        ];
    }
}
