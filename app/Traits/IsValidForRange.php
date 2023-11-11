<?php

namespace App\Traits;

trait IsValidForRange
{
    public function scopeIsValidForRange($query, array $range = [])
    {
        return $query->where(function ($query) use ($range) {
            return $query->where('start_date', '<=', reset($range))
                ->where('end_date', '>=', end($range));
        });
    }
}
