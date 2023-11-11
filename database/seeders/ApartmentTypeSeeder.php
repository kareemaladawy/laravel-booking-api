<?php

namespace Database\Seeders;

use App\Models\ApartmentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApartmentType::create(['name' => 'Entire apartment']);
        ApartmentType::create(['name' => 'Entire studio']);
        ApartmentType::create(['name' => 'Private suite']);
    }
}
