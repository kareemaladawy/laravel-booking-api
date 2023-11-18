<?php

namespace Tests\Feature;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\City;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class BookingsTest extends TestCase
{
    private function create_apartment(): Apartment
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);
        $property = Property::factory()->create([
            'owner_id' => $owner->id,
        ]);

        return Apartment::create([
            'name' => 'Apartment',
            'property_id' => $property->id,
            'adult_capacity' => 3,
            'children_capacity' => 2,
        ]);
    }

    public function test_user_has_access_to_bookings()
    {
        $user = User::factory()->create(['role_id' => Role::USER]);

        $response = $this->actingAs($user)->getJson('/api/v1/user/bookings');

        $response->assertStatus(200);
    }

    public function test_owner_does_not_have_access_to_bookings()
    {
        $owner = User::factory()->create(['role_id' => Role::OWNER]);

        $response = $this->actingAs($owner)->getJson('/api/v1/user/bookings');

        $response->assertForbidden();
    }

    public function test_user_can_book_apartment_all_cases()
    {
        $user = User::factory()->create([
            'role_id' => Role::USER
        ]);

        $apartment = $this->create_apartment();

        $apartment->prices()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(20)->toDateString(),
            'price_per_night' => 100
        ]);

        $booking_params = [
            'apartment_id' => $apartment->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
        ];

        $response = $this->actingAs($user)->postJson('/api/v1/user/bookings', $booking_params);
        $response->assertStatus(201);

        $response = $this->actingAs($user)->postJson('/api/v1/user/bookings', $booking_params);
        $response->assertStatus(422);

        $booking_params['start_date'] = now()->addDays(4);
        $booking_params['end_date'] = now()->addDays(7);
        $booking_params['adult_guests'] = 5;
        $response = $this->actingAs($user)->postJson('/api/v1/user/bookings', $booking_params);
        $response->assertStatus(422);

        $booking_params['start_date'] = now()->addDays(4);
        $booking_params['end_date'] = now()->addDays(7);
        $booking_params['adult_guests'] = 3;
        $response = $this->actingAs($user)->postJson('/api/v1/user/bookings', $booking_params);
        $response->assertStatus(201);

        $booking_params['start_date'] = now()->addDays(21);
        $booking_params['end_date'] = now()->addDays(24);
        $response = $this->actingAs($user)->postJson('/api/v1/user/bookings', $booking_params);
        $response->assertStatus(422);
    }

    public function test_user_can_get_only_their_bookings()
    {
        $user1 = User::factory()->create(['role_id' => Role::USER]);
        $user2 = User::factory()->create(['role_id' => Role::USER]);

        $apartment = $this->create_apartment();

        $booking1 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user1->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'adult_guests' => 1,
            'children_guests' => 0,
        ]);
        $booking2 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user2->id,
            'start_date' => now()->addDay(3),
            'end_date' => now()->addDays(4),
            'adult_guests' => 2,
            'children_guests' => 0,
        ]);

        $response = $this->actingAs($user1)->getJson('/api/v1/user/bookings');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $booking1->id]);
        $response->assertJsonMissing(['id' => $booking2->id]);

        $response = $this->actingAs($user1)->getJson('/api/v1/user/bookings/' . $booking1->id);
        $response->assertStatus(200);
        $response->assertJsonCount(8); // BookingResource keys
        $response->assertJsonFragment(['adult_guests' => $booking1->adult_guests]);

        $response = $this->actingAs($user1)->getJson('/api/v1/user/bookings/' . $booking2->id);
        $response->assertStatus(403);
    }

    public function test_user_can_cancel_their_booking_but_still_view_it()
    {
        $user = User::factory()->create(['role_id' => Role::USER]);

        $apartment = $this->create_apartment();

        $booking1 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'adult_guests' => 1,
            'children_guests' => 0,
        ]);
        $booking2 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user->id,
            'start_date' => now()->addDay(3),
            'end_date' => now()->addDays(4),
            'adult_guests' => 2,
            'children_guests' => 0,
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/user/bookings/' . $booking1->id . '/cancel');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->getJson('/api/v1/user/bookings');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['id' => $booking1->id]);
        $response->assertJsonFragment(['id' => $booking2->id]);

        $response = $this->actingAs($user)->getJson('/api/v1/user/bookings/' . $booking1->id);
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $booking1->id]);
    }

    public function test_user_cannot_cancel_other_users_bookings()
    {
        $user1 = User::factory()->create(['role_id' => Role::USER]);
        $user2 = User::factory()->create(['role_id' => Role::USER]);

        $apartment = $this->create_apartment();

        $booking1 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user1->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'adult_guests' => 1,
            'children_guests' => 0,
        ]);
        $booking2 = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user2->id,
            'start_date' => now()->addDay(3),
            'end_date' => now()->addDays(4),
            'adult_guests' => 2,
            'children_guests' => 0,
        ]);

        $response = $this->actingAs($user1)->putJson('/api/v1/user/bookings/'  . $booking2->id . '/cancel');
        $response->assertStatus(403);
    }

    public function test_user_can_add_rating_and_review_commnet_to_their_booking()
    {
        $user1 = User::factory()->create(['role_id' => Role::USER]);
        $user2 = User::factory()->create(['role_id' => Role::USER]);

        $apartment = $this->create_apartment();

        $booking = Booking::create([
            'apartment_id' => $apartment->id,
            'user_id' => $user1->id,
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(2),
            'guests_adults' => 1,
            'guests_children' => 0,
        ]);

        $response = $this->actingAs($user2)->putJson('/api/v1/user/bookings/' . $booking->id, []);
        $response->assertStatus(403);

        $response = $this->actingAs($user1)->putJson('/api/v1/user/bookings/' . $booking->id, [
            'rating' => 11
        ]);
        $response->assertStatus(422);

        $response = $this->actingAs($user1)->putJson('/api/v1/user/bookings/' . $booking->id, [
            'rating' => 10,
            'review_comment' => 'Too short comment.'
        ]);
        $response->assertStatus(422);

        $correctData = [
            'rating' => 10,
            'review_comment' => 'Comment with a good length to be accepted.'
        ];
        $response = $this->actingAs($user1)->putJson('/api/v1/user/bookings/' . $booking->id, $correctData);
        $response->assertStatus(200);
    }
}
