<?php

namespace Database\Seeders;

use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerformanceTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([
        //     RoleSeeder::class,
        //     AdminUserSeeder::class,
        //     PermissionSeeder::class
        // ]);

        // $this->callWith(Performance\UserSeeder::class, [
        //     // 'owners' => 700,
        //     'users' => 700
        // ]);

        // $this->callWith(Performance\CountrySeeder::class, [
        //     'count' => 100
        // ]);

        // $this->callWith(Performance\CitySeeder::class, [
        //     'count' => 500
        // ]);

        // $this->callWith(Performance\GeoobjectSeeder::class, [
        //     'count' => 200
        // ]);

        // $this->callWith(Performance\PropertySeeder::class, [
        //     'count' => 500
        // ]);

        // $this->callWith(Performance\ApartmentSeeder::class, [
        //     'count' => 500
        // ]);

        $this->callWith(Performance\BookingSeeder::class, [
            'withRatings' => 10,
            'withoutRatings' => 10
        ]);
    }
}
