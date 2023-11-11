<?php

namespace Database\Seeders\Performance;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $users = 100): void
    {
        $owner_role = Role::OWNER;
        $user_role = Role::USER;

        $owners = [];

        for ($i = 1; $i <= $owners; $i++) {
            $owners[] = [
                'name' => 'Owner ' . $i,
                'email' => 'email' . $i . '@e.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'role_id' => $owner_role
            ];

            if ($i % 500 == 0 || $i == $owners) {
                User::insert($owners);
                $owners = [];
            }
        }

        $users = [];

        for ($i = 1; $i <= $users; $i++) {
            $users[] = [
                'name' => 'User ' . $i,
                'email' => 'gmail' . $i . '@e.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'role_id' => $user_role
            ];

            if ($i % 500 == 0 || $i == $users) {
                User::insert($users);
                $users = [];
            }
        }
    }
}
