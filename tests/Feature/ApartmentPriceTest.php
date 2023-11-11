<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use App\Services\PricingService;
use Tests\TestCase;

class ApartmentPriceTest extends TestCase
{
    private function create_apartment(): Apartment
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $property = Property::factory()->create([
            'owner_id' => $owner->id
        ]);

        return Apartment::factory()->create([
            'property_id' => $property->id
        ]);
    }

    public function test_apartment_calculates_price_for_1_day_correctly()
    {
        $apartment = $this->create_apartment();

        ApartmentPrice::create([
            'apartment_id' => $apartment->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'price_per_night' => 100
        ]);

        $cost = PricingService::calculateApartmentPriceForDates(
            $apartment->prices,
            now()->toDateString(),
            now()->toDateString()
        );

        $this->assertEquals(100, $cost);
    }

    public function test_apartment_calculates_price_for_2_days_correctly()
    {
        $apartment = $this->create_apartment();

        ApartmentPrice::create([
            'apartment_id' => $apartment->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'price_per_night' => 100
        ]);

        $cost = PricingService::calculateApartmentPriceForDates(
            $apartment->prices,
            now()->toDateString(),
            now()->addDay()->toDateString()
        );

        $this->assertEquals(200, $cost);
    }

    public function test_apartment_calculate_price_multiple_ranges_correctly()
    {
        $apartment = $this->create_apartment();

        ApartmentPrice::create([
            'apartment_id' => $apartment->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'price_per_night' => 100
        ]);

        ApartmentPrice::create([
            'apartment_id' => $apartment->id,
            'start_date' => now()->addDays(3)->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'price_per_night' => 150
        ]);

        $cost = PricingService::calculateApartmentPriceForDates(
            $apartment->prices,
            now()->toDateString(),
            now()->addDays(5)->toDateString()
        );

        $this->assertEquals(750, $cost);
    }

    public function test_owner_can_create_price_for_thier_apartments_only()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $property = Property::factory()->create([
            'owner_id' => $owner->id
        ]);

        $apartment = Apartment::factory()->create([
            'property_id' => $property->id
        ]);

        $response = $this->actingAs($owner)->postJson(
            '/api/v1/owner/properties/' . $property->id . '/apartments/' . $apartment->id . '/prices',
            [
                'start_date' => now()->addDays(2)->toDateString(),
                'end_date' => now()->addDays(10)->toDateString(),
                'price_per_night' => 20
            ]
        );

        $response->assertStatus(201);
    }
}
