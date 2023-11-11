<?php

namespace Database\Seeders;

use App\Models\ApartmentPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApartmentPrice::factory(100)->create();
    }
}
