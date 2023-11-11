<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BedType;
use App\Models\RoomType;
use Database\Seeders\Performance\BookingSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // RoleSeeder::class,
            // AdminUserSeeder::class,
            // OwnerUserSeeder::class,
            // PermissionSeeder::class,
            // CountrySeeder::class,
            // CitySeeder::class,
            // GeoobjectSeeder::class,
            // PropertySeeder::class,
            // FacilityCategorySeeder::class,
            // FacilitySeeder::class,
            // ApartmentTypeSeeder::class,
            // ApartmentSeeder::class,
            // ApartmentFacilitySeeder::class,
            // RoomTypeSeeder::class,
            // RoomSeeder::class,
            // BedTypeSeeder::class,
            // BedSeeder::class
        ]);
    }
}
