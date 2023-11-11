<?php

namespace App\Rules;

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ApartmentAvailableRule implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $apartment = Apartment::find($value);

        if (isset($this->data['start_date']) && isset($this->data['end_date'])) {
            if ($this->data['end_date'] < $this->data['start_date']) {
                $fail('Please specify correct date range.');
                return;
            }

            if ($apartment->prices()->max('end_date') < $this->data['end_date']) {
                $fail('Sorry, this apartment is not available for the specified date range.');
                return;
            }

            if (ApartmentPrice::where('apartment_id', $value)
                ->isValidForRange([$this->data['start_date'], $this->data['end_date']])
                ->exists()
            ) {
                if (Booking::where('apartment_id', $value)
                    ->isValidForRange([$this->data['start_date'], $this->data['end_date']])
                    ->exists()
                ) {
                    $fail('Sorry, this apartment is booked for the specified date range.');
                }
            } else {
                $fail('Sorry, this apartment is not available for the specified date range');
            }
        }
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
