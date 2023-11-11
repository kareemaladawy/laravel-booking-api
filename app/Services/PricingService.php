<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class PricingService
{
    public static function calculateApartmentPriceForDates(
        Collection $apartment_prices,
        ?string $start_date,
        ?string $end_date
    ): int {

        $start_date = Carbon::parse($start_date)->startOfDay();
        $end_date = Carbon::parse($end_date)->endOfDay();

        $cost = 0;

        while ($start_date->lte($end_date)) {
            $cost += $apartment_prices->where(function ($price) use ($start_date) {
                return Carbon::parse($price['start_date'])->lte($start_date)
                    && Carbon::parse($price['end_date'])->gte($start_date);
            })->value('price_per_night');

            $start_date->addDay();
        }

        return $cost;
    }
}
