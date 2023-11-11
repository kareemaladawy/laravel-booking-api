<?php

namespace Tests\Feature;

use App\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_registration_fails_with_admin_role(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'name',
            'email' => 'admin@email.com',
            'password' => 'password',
            'role_id' => Role::ADMINISTRATOR
        ]);

        $response->assertStatus(422);
    }

    public function test_registration_succeeds_with_owner_role(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Owner test',
            'email' => fake()->email(),
            'password' => 'password',
            'role_id' => Role::OWNER
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
        ]);
    }

    public function test_registration_succeeds_with_user_role(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'User test',
            'email' => fake()->email(),
            'password' => 'password',
            'role_id' => Role::USER
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'access_token',
        ]);
    }
}
