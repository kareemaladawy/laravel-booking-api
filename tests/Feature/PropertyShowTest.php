<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\City;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyShowTest extends TestCase
{
    public function test_property_show_loads_the_correct_property_with_correct_details()
    {
        $city = City::factory()->create();
        $property = Property::factory()->create([
            'city_id' => $city->id
        ]);

        $largeApartment = Apartment::factory()
            ->has(ApartmentPrice::factory(), 'prices')
            ->create([
                'property_id' => $property->id,
                'adult_capacity' => 6,
                'children_capacity' => 4
            ]);

        $midSizeApartment = Apartment::factory()
            ->has(ApartmentPrice::factory(), 'prices')
            ->create([
                'property_id' => $property->id,
                'adult_capacity' => 4,
                'children_capacity' => 1
            ]);

        $smallApartment = Apartment::factory()
            ->has(ApartmentPrice::factory(), 'prices')
            ->create([
                'property_id' => $property->id,
                'adult_capacity' => 2,
                'children_capacity' => 4
            ]);

        $facility = Facility::create([
            'category_id' => FacilityCategory::value('id'),
            'name' => 'Some facility'
        ]);

        $largeApartment->facilities()->attach($facility->id);

        $response = $this->getJson('/api/v1/properties/view/' . $property->id);

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'apartments');
        $response->assertJsonPath('name', $property->name);

        $response = $this->getJson('/api/v1/search?city_id=' . $city->id . '&adult_capacity=2&children_capacity=2');
        $response->assertStatus(200);
        $response->assertJsonPath('0.apartments.0.facilities', null);
    }
}
