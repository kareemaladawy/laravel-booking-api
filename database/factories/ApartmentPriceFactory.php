<?php

namespace Database\Factories;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApartmentPrice>
 */
class ApartmentPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'apartment_id' => Apartment::inRandomOrder()->value('id'),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(rand(2, 500))->toDateString(),
            'price_per_night' => rand(50, 1000)
        ];
    }
}
