<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ApartmentShowTest extends TestCase
{
    public function test_apartment_show_loads_apartment_with_facilities(): void
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $property = Property::factory()->create([
            'owner_id' => $owner->id
        ]);
        $apartment = Apartment::factory()->create([
            'property_id' => $property->id,
        ]);

        $facility_category_1 = FacilityCategory::create([
            'name' => 'Cat 1'
        ]);

        $facility_category_2 = FacilityCategory::create([
            'name' => 'Cat 2'
        ]);

        $facility_1_1 = Facility::create([
            'category_id' => $facility_category_1->id,
            'name' => 'Fac 1 1'
        ]);

        $facility_1_2 = Facility::create([
            'category_id' => $facility_category_1->id,
            'name' => 'Fac 1 2'
        ]);

        $facility_2_1 = Facility::create([
            'category_id' => $facility_category_2->id,
            'name' => 'Fac 2 1'
        ]);

        $apartment->facilities()->attach([
            $facility_1_1->id, $facility_1_2->id, $facility_2_1->id
        ]);

        $expected_facility_array = [
            $facility_category_1->name => [
                $facility_1_1->name,
                $facility_1_2->name,
            ],
            $facility_category_2->name => [
                $facility_2_1->name,
            ],
        ];


        $response = $this->getJson('/api/v1/apartments/view/' . $apartment->id);
        $response->assertStatus(200);
        $response->assertJsonPath('name', $apartment->name);
        $response->assertJsonFragment($expected_facility_array, 'facility_categories');
    }
}
