<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('admin');

        $this->token = $user->createToken('auth_token')->plainTextToken;
    }

    protected function apiPost($url, $data)
    {
        return $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("/api/v1" . $url, $data);
    }

    protected function apiGet($url)
    {
        return $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1" . $url);
    }
}
