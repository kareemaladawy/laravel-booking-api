<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertiesTest extends TestCase
{
    public function test_owner_has_access_to_properties()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);

        $response = $this->actingAs($owner)->getJson('/api/v1/owner/properties');

        $response->assertStatus(200);
    }

    public function test_user_does_not_have_access_to_properties()
    {
        $user = User::factory()->create(['role_id' => Role::USER]);

        $response = $this->actingAs($user)->getJson('/api/v1/owner/properties');

        $response->assertForbidden();
    }

    public function test_property_owner_can_add_property()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);

        $response = $this->actingAs($owner)->postJson('/api/v1/owner/properties', [
            'name' => 'Central Hotel',
            'city_id' => City::value('id'),
            'address_street' => 'Street Address 1',
            'address_postcode' => '12345'
        ]);

        $response->assertStatus(201);
    }

    public function test_property_owner_can_add_photo_to_property()
    {
        Storage::fake();

        $owner = User::factory()->create(['role_id' => Role::OWNER]);

        $property = Property::factory()->create([
            'owner_id' => $owner->id
        ]);

        $response = $this->actingAs($owner)->postJson('/api/v1/owner/properties/' . $property->id . '/photos', [
            'photos' => [UploadedFile::fake()->image('property.png')]
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            [
                'id',
                'file_name',
                'thumbnail',
                'order'
            ]
        ]);
    }

    public function test_property_owner_can_reorder_photos_of_property()
    {
        Storage::fake();

        $owner = User::factory()->create(['role_id' => Role::OWNER]);

        $property = Property::factory()->create([
            'owner_id' => $owner->id
        ]);

        $photo1 = $this->actingAs($owner)->postJson('/api/v1/owner/properties/' . $property->id . '/photos', [
            'photos' => [UploadedFile::fake()->image('photo1.png')]
        ]);
        $photo2 = $this->actingAs($owner)->postJson('/api/v1/owner/properties/' . $property->id . '/photos', [
            'photos' => [UploadedFile::fake()->image('photo2.png')]
        ]);

        $photo1->assertStatus(201);
        $photo2->assertStatus(201);

        $new_order = $photo2->json('1.order') - 1;

        $response = $this->actingAs($owner)->putJson(
            '/api/v1/owner/properties/' . $property->id . '/photos/' . $photo2->json('1.id') . '/reorder',
            [
                'new_order' => $new_order
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonFragment(['new_order' => $new_order]);

        $this->assertDatabaseHas('media', ['file_name' => 'photo1.png', 'order_column' => 2]);
        $this->assertDatabaseHas('media', ['file_name' => 'photo2.png', 'order_column' => 1]);
    }
}
