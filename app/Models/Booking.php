<?php

namespace App\Models;

use App\Builders\BookingBuilder;
use App\Traits\IsValidForRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes, IsValidForRange;

    protected $fillable = [
        'apartment_id',
        'user_id',
        'start_date',
        'end_date',
        'adult_guests',
        'children_guests',
        'total_price',
        'rating',
        'review_comment'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function newEloquentBuilder($query): BookingBuilder
    {
        return new BookingBuilder($query);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
