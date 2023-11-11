<?php

namespace App\Models;

use App\Builders\BaseBuilder;
use App\Builders\PropertyBuilder;
use App\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Property extends Model implements HasMedia
{
    use HasFactory, HasEagerLimit, InteractsWithMedia;

    protected $fillable = [
        'owner_id',
        'name',
        'city_id',
        'address_street',
        'address_postcode',
        'lat',
        'long',
        'bookings_avg_rating',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public static function booted()
    {
        parent::booted();
        self::observe(PropertyObserver::class);

        static::addGlobalScope('active', function (Builder $query) {
            $query->where('active', 1);
        });
    }

    public function newEloquentBuilder($query): PropertyBuilder
    {
        return new PropertyBuilder($query);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->onlyKeepLatest(10);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(800);
    }

    public function address(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->address_street
                . ', ' . $this->address_postcode
                . ', ' . $this->city->name
        );
    }


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Apartment::class);
    }
}
