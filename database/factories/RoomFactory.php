<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
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
            'room_type_id' => RoomType::inRandomOrder()->value('id'),
            'name' => 'Room ' . fake()->text(7)
        ];
    }
}
