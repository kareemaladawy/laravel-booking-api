<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $all_roles = Role::all()->keyBy('id');

        $permissions = [
            'manage-bookings' => [Role::USER],
            'manage-properties' => [Role::OWNER],
        ];

        foreach ($permissions as $key => $roles) {
            $permission = Permission::create(['name' => $key]);
            foreach ($roles as $role_id) {
                $all_roles[$role_id]->permissions()->attach($permission->id);
            }
        }
    }
}
