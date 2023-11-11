<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'superadmin' . fake()->firstName() . '@booking.com',
            'password' => bcrypt('admin'),
            'email_verified_at' => now(),
            'role_id' => Role::ADMINISTRATOR
        ]);
    }
}
