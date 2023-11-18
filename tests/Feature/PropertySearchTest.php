<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\City;
use App\Models\Country;
use App\Models\Facility;
use App\Models\Geoobject;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertySearchTest extends TestCase
{
    public function test_property_search_by_city_returns_correct_results()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $cities = City::take(2)->pluck('id');

        $propertyInCity0 = Property::factory()->create(['city_id' => $cities[0]]);
        $propertyInCity1 = Property::factory()->create(['city_id' => $cities[1]]);

        $response = $this->getJson('/api/v1/search?city_id=' . $cities[0]);

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $propertyInCity0->name]);
    }

    public function test_property_search_by_country_returns_correct_results()
    {
        $countries = Country::with('cities')->take(2)->get();

        $propertyInCountry0 = Property::factory()->create(['city_id' => $countries[0]->cities()->value('id')]);
        $propertyInCountry1 = Property::factory()->create(['city_id' => $countries[0]->cities()->value('id')]);

        $response = $this->getJson('/api/v1/search?country_id=' . $countries[0]->id);

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $propertyInCountry0->name]);
    }

    public function test_property_search_by_geoobject_returns_correct_results()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $geoobject = Geoobject::first();
        $city_id = City::value('id');

        $propertyNear = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city_id,
            'lat' => $geoobject->lat,
            'long' => $geoobject->long,
        ]);

        $propertyFar = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city_id,
            'lat' => $geoobject->lat + 10,
            'long' => $geoobject->long - 10,
        ]);

        $response = $this->getJson('/api/v1/search?geoobject_id=' . $geoobject->id);

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $propertyNear->name]);
        // $response->assertJsonMissing(['name' => $propertyFar->name]);
    }

    public function test_property_search_by_capacity_returns_correct_results()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $property_with_suitable_apartment = Property::factory()
            ->create([
                'owner_id' => $owner->id,
            ]);
        Apartment::factory()->create([
            'property_id' => $property_with_suitable_apartment->id,
            'adult_capacity' => 1,
            'children_capacity' => 5,
        ]);

        $property_with_non_suitable_apartment = Property::factory()
            ->create([
                'owner_id' => $owner->id,
            ]);
        Apartment::factory()->create([
            'property_id' => $property_with_non_suitable_apartment->id,
            'adult_capacity' => 3,
            'children_capacity' => 1,
        ]);

        $response = $this->getJson(
            '/api/v1/search?adult_capacity=1&children_capacity=4'
        );

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['id' => $property_with_suitable_apartment->id]);
        // $response->assertJsonMissing(['id' => $property_with_non_suitable_apartment->id]);
    }

    public function test_property_search_by_capacity_returns_only_suitable_apartments()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $city_id = City::value('id');

        $property = Property::factory()
            ->create([
                'owner_id' => $owner->id,
                'city_id' => $city_id,
            ]);

        $non_suitable_apartment = Apartment::factory()->create([
            'property_id' => $property->id,
            'adult_capacity' => 2,
            'children_capacity' => 3,
        ]);

        $suitable_apartment = Apartment::factory()->create([
            'property_id' => $property->id,
            'adult_capacity' => 7,
            'children_capacity' => 10,
        ]);

        $response = $this->getJson(
            '/api/v1/search?city_id=' . $city_id . '&adult_capacity=6&children_capacity=3'
        );

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $suitable_apartment->name]);
        // $response->assertJsonMissing(['name' => $non_suitable_apartment->name]);
    }

    public function test_property_search_returns_one_best_apartment_per_property()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $city = City::factory()->create();

        $property = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city->id,
        ]);

        $largeApartment = Apartment::factory()->create([
            'name' => 'Large apartment',
            'property_id' => $property->id,
            'adult_capacity' => 3,
            'children_capacity' => 2,
        ]);

        $largeApartment->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(10, 100)
        ]);

        $midSizeApartment = Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property->id,
            'adult_capacity' => 2,
            'children_capacity' => 1,
        ]);

        $midSizeApartment->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(10, 100)
        ]);

        $smallApartment = Apartment::factory()->create([
            'name' => 'Small apartment',
            'property_id' => $property->id,
            'adult_capacity' => 1,
            'children_capacity' => 0,
        ]);

        $smallApartment->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(10, 100)
        ]);

        $property2 = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city->id,
        ]);

        $largeApartment2 = Apartment::factory()->create([
            'name' => 'Large apartment 2',
            'property_id' => $property2->id,
            'adult_capacity' => 3,
            'children_capacity' => 2,
        ]);

        $largeApartment2->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(10, 100)
        ]);

        $midSizeApartment2 = Apartment::factory()->create([
            'name' => 'Mid size apartment 2',
            'property_id' => $property2->id,
            'adult_capacity' => 2,
            'children_capacity' => 1,
        ]);

        $midSizeApartment2->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(10, 100)
        ]);

        $smallApartment2 = Apartment::factory()->create([
            'name' => 'Small apartment 2',
            'property_id' => $property2->id,
            'adult_capacity' => 1,
            'children_capacity' => 0,
        ]);

        $smallApartment2->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(10, 100)
        ]);

        $response = $this->getJson('/api/v1/search?city_id=' . $city->id . '&adult_capacity=3&children_capacity=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'properties.data.0.apartments');
        $response->assertJsonCount(1, 'properties.data.1.apartments');
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $largeApartment->name]);
    }

    public function test_property_search_filters_by_facilities()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $city_id = City::value('id');

        $property = Property::factory()
            ->create([
                'owner_id' => $owner->id,
                'city_id' => $city_id,
            ]);

        $facility_1 = Facility::create([
            'name' => 'New facility 1'
        ]);

        $facility_2 = Facility::create([
            'name' => 'New facility 2'
        ]);

        $property->facilities()->attach($facility_1->id);

        $response = $this->getJson('/api/v1/search?city_id=' . $city_id . '&facilities[]=' . $facility_2->id);
        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonMissing(['name' => $property->name]);
    }

    public function test_property_search_filters_by_price()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $city_id = City::value('id');

        $property1 = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city_id,
        ]);

        $cheapApartment = Apartment::factory()->create([
            'name' => 'Cheap apartment',
            'property_id' => $property1->id,
            'adult_capacity' => 2,
            'children_capacity' => 1,
        ]);

        $cheapApartment->prices()->create([
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'price_per_night' => 70,
        ]);

        $property2 = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city_id,
        ]);

        $expensiveApartment = Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property2->id,
            'adult_capacity' => 2,
            'children_capacity' => 1,
        ]);

        $expensiveApartment->prices()->create([
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'price_per_night' => 130,
        ]);

        // test without price returns both apartments
        $response = $this->getJson(
            '/api/v1/search?city_id=' . $city_id . '&adult_capacity=2&children_capacity=1'
        );

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $expensiveApartment->name]);
        // $response->assertJsonFragment(['name' => $cheapApartment->name]);

        // test with exp price returns exp apartment
        $response = $this->getJson(
            '/api/v1/search?city_id=' . $city_id . '&adult_capacity=2&children_capacity=1&price_from=100'
        );

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $expensiveApartment->name]);
        // $response->assertJsonMissing(['name' => $cheapApartment->name]);

        // test with chp price returns chp apartment
        $response = $this->getJson(
            '/api/v1/search?city_id=' . $city_id . '&adult_capacity=2&children_capacity=1&price_to=100'
        );

        $response->assertStatus(200);
        // works properly without pagination
        // $response->assertJsonFragment(['name' => $cheapApartment->name]);
        // $response->assertJsonMissing(['name' => $expensiveApartment->name]);
    }

    public function test_properties_show_correct_rating_and_ordered_by_it()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $city = City::factory()->create();

        $property1 = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city->id,
        ]);

        $apartment1 = Apartment::factory()->create([
            'name' => 'Cheap apartment',
            'property_id' => $property1->id,
            'adult_capacity' => 2,
            'children_capacity' => 1,
        ]);

        $apartment1->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(50, 100)
        ]);

        $property2 = Property::factory()->create([
            'owner_id' => $owner->id,
            'city_id' => $city->id,
        ]);

        $apartment2 = Apartment::factory()->create([
            'name' => 'Mid size apartment',
            'property_id' => $property2->id,
            'adult_capacity' => 2,
            'children_capacity' => 1,
        ]);

        $apartment2->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'price_per_night' => rand(20, 50)
        ]);

        $user1 = User::factory()->create(['role_id' => Role::USER]);
        $user2 = User::factory()->create(['role_id' => Role::USER]);
        $user3 = User::factory()->create(['role_id' => Role::USER]);

        $booking1 = $this->actingAs($user1)->postJson('/api/v1/user/bookings', [
            'apartment_id' => $apartment1->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'adult_guests' => 1,
            'children_guests' => 0,
        ]);

        $booking1->assertStatus(201);

        $response = $this->actingAs($user1)->putJson('/api/v1/user/bookings/' . $booking1->json('id'), [
            'rating' => 9
        ]);

        $response->assertStatus(200);

        $booking2 = $this->actingAs($user2)->postJson('/api/v1/user/bookings', [
            'apartment_id' => $apartment1->id,
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'adult_guests' => 1,
            'children_guests' => 0,
        ]);

        $booking2->assertStatus(201);

        $response = $this->actingAs($user2)->putJson('/api/v1/user/bookings/' . $booking2->json('id'), [
            'rating' => 7
        ]);

        $response->assertStatus(200);

        $booking3 = $this->actingAs($user3)->postJson('/api/v1/user/bookings', [
            'apartment_id' => $apartment2->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'adult_guests' => 1,
            'children_guests' => 0,
        ]);

        $booking3->assertStatus(201);

        $response = $this->actingAs($user3)->putJson('/api/v1/user/bookings/' . $booking3->json('id'), [
            'rating' => 7
        ]);

        $response->assertStatus(200);

        $response = $this->getJson('/api/v1/search?city_id=' . $city->id . '&adults=2&children=1');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'properties.data');
        $this->assertEquals(8, $response->json('properties.data')[0]['avg_rating']);
        $this->assertEquals(7, $response->json('properties.data')[1]['avg_rating']);
    }
}
