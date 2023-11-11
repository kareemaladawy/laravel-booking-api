<?php

namespace Database\Seeders\Performance;

use App\Models\Apartment;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $count = 100): void
    {
        $propertyMin = Property::min('id');
        $propertyMax = Property::max('id');

        $apartments = [];

        for ($i = 1; $i <= $count; $i++) {
            $apartments[] = [
                'property_id' => rand($propertyMin, $propertyMax),
                'name' => 'Apartment ' . $i,
                'adult_capacity' => rand(1, 5),
                'children_capacity' => rand(1, 5),
            ];

            if ($i % 500 == 0 || $i == $count) {
                Apartment::insert($apartments);
                $apartments = [];
            }
        }
    }
}
