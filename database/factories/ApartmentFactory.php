<?php

namespace Database\Factories;

use App\Models\ApartmentType;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Apartment>
 */
class ApartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::inRandomOrder()->value('id'),
            'apartment_type_id' => ApartmentType::inRandomOrder()->value('id'),
            'name' => fake()->text(20),
            'adult_capacity' => rand(1, 5),
            'children_capacity' => rand(1, 5),
            'size' => rand(20, 100)
        ];
    }
}
