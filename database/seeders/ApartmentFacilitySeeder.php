<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::find([1, 2, 3])->pluck('id')->toArray();

        foreach (Apartment::all() as $apartment) {
            $apartment->facilities()->attach($facilities);
        }
    }
}
