<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::where('category_id', null)->pluck('id')->toArray();

        foreach (Property::take(5)->get() as $property) {
            $property->facilities()->sync($facilities);
        }
    }
}
