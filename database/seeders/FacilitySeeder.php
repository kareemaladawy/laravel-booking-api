<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Apartment facilities:
        Facility::create(['category_id' => 1, 'name' => 'Linen']);
        Facility::create(['category_id' => 1, 'name' => 'Wardrobe or closet']);
        Facility::create(['category_id' => 2, 'name' => 'Electric kettle']);
        Facility::create(['category_id' => 2, 'name' => 'Microwave']);
        Facility::create(['category_id' => 2, 'name' => 'Washing mashine']);
        Facility::create(['category_id' => 3, 'name' => 'Private bathroom']);
        Facility::create(['category_id' => 3, 'name' => 'Shower']);
        Facility::create(['category_id' => 3, 'name' => 'Towels']);
        Facility::create(['category_id' => 4, 'name' => 'Drying rack for clothing']);
        Facility::create(['category_id' => 5, 'name' => 'No smoking']);
        Facility::create(['category_id' => 5, 'name' => 'Fan']);
        Facility::create(['category_id' => 6, 'name' => 'WiFi']);
        Facility::create(['category_id' => 6, 'name' => 'TV']);

        // Property facilities:
        Facility::create(['name' => 'Family rooms']);
        Facility::create(['name' => 'Smoking rooms']);
        Facility::create(['name' => 'Free WiFi']);
        Facility::create(['name' => 'Parking']);
        Facility::create(['name' => 'Pets allowed']);
        Facility::create(['name' => 'Swimming pool']);
    }
}
