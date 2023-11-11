<?php

namespace Database\Seeders;

use App\Models\BedType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BedType::create(['name' => 'Single bed']);
        BedType::create(['name' => 'Large double bed']);
        BedType::create(['name' => 'Extra large double bed']);
        BedType::create(['name' => 'Sofa bed']);
    }
}
