<?php

namespace Database\Seeders\Performance;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $withRatings = 10, int $withoutRatings = 10): void
    {
        $users = User::where('role_id', Role::USER)->pluck('id');
        $apartmentMin = Apartment::min('id');
        $apartmentMax = Apartment::max('id');

        $bookings = [];

        for ($i = 1; $i <= $withoutRatings; $i++) {
            $bookings[] = [
                'apartment_id' => rand($apartmentMin, $apartmentMax),
                'start_date' => now()->addDays(rand(1, 200))->toDateTimeString(),
                'end_date' => now()->addDays(rand(1, 200))->addDays(rand(2, 7))->toDateTimeString(),
                'adult_guests' => rand(1, 5),
                'children_guests' => rand(1, 5),
                'total_price' => rand(100, 2000),
                'user_id' => $users->random()
            ];

            if ($i % 50 == 0 || $i == $withoutRatings) {
                Booking::insert($bookings);
                unset($bookings);
            }
        }

        for ($i = 1; $i <= $withRatings; $i++) {
            $bookings[] = [
                'apartment_id' => rand($apartmentMin, $apartmentMax),
                'start_date' => now()->addDays(rand(1, 200))->toDateTimeString(),
                'end_date' => now()->addDays(rand(1, 200))->addDays(rand(2, 7))->toDateTimeString(),
                'adult_guests' => rand(1, 5),
                'children_guests' => rand(1, 5),
                'total_price' => rand(100, 2000),
                'user_id' => $users->random(),
                'rating' => random_int(1, 10)
            ];

            if ($i % 50 == 0 || $i == $withoutRatings) {
                Booking::insert($bookings);
                unset($bookings);
            }
        }
    }
}
