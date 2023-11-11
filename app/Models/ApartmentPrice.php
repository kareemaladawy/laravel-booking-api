<?php

namespace App\Models;

use App\Traits\IsValidForRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApartmentPrice extends Model
{
    use HasFactory, IsValidForRange;

    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'apartment_id',
        'start_date',
        'end_date',
        'price_per_night'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];
}
