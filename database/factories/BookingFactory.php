<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'apartment_id' => rand(5, 100),
            'start_date' => now()->addDays(rand(1, 200))->toDateTimeString(),
            'end_date' => now()->addDays(rand(1, 200))->addDays(rand(2, 7))->toDateTimeString(),
            'adult_guests' => rand(1, 5),
            'children_guests' => rand(1, 5),
            'total_price' => rand(100, 2000),
            'user_id' => User::where('role_id', Role::USER)->inRandomOrder()->value('id')
        ];
    }
}
