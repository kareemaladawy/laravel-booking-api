<?php

namespace App\Models;

use App\Builders\ApartmentBuilder;
use App\Builders\BaseBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;


class Apartment extends Model
{
    use HasFactory, HasEagerLimit;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'property_id',
        'apartment_type_id',
        'name',
        'adult_capacity',
        'children_capacity',
        'size',
        'bathrooms',
        'active',
    ];

    public static function booted()
    {
        static::addGlobalScope('active', function (Builder $query) {
            $query->where('active', 1);
        });
    }

    public function newEloquentBuilder($query): ApartmentBuilder
    {
        return new ApartmentBuilder($query);
    }

    public function prices()
    {
        return $this->hasMany(ApartmentPrice::class);
    }

    public function apartment_type()
    {
        return $this->belongsTo(ApartmentType::class)->withDefault([
            'name' => 'None'
        ]);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function owner()
    {
        return $this->belongsToThrough(
            User::class,
            Property::class,
            foreignKeyLookup: [User::class => 'owner_id']
        );
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function beds()
    {
        return $this->hasManyThrough(Bed::class, Room::class);
    }

    public function bedslist(): Attribute
    {
        $beds_list = '';

        $all_beds = $this->beds;
        $beds_by_type = $all_beds->groupBy('bed_type.name');

        if ($beds_by_type->count() == 1) {
            $beds_list = $all_beds->count() . ' ' . str($beds_by_type->keys()[0])->plural($all_beds->count());
        } else if ($beds_by_type->count() > 1) {
            $beds_list_array = [];

            foreach ($beds_by_type as $bed_type => $beds) {
                $beds_list_array[] = $beds->count() . ' ' . str($bed_type)->plural($beds->count());
            }

            $beds_list = $all_beds->count() . ' beds (' . implode(', ', $beds_list_array) . ')';
        }

        return Attribute::make(
            get: fn () => $beds_list
        );
    }
}
