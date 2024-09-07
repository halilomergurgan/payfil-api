<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\BaseTest;

class UserTest extends BaseTest
{
    public function test_user_can_login_with_valid_credentials_and_admin_role()
    {
        // Login işlemini yap
        $response = $this->apiPost('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

         $response->json('token');

        $user = User::where('email', 'user@example.com')->first();

        $this->assertTrue($user->hasRole('admin'));

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->apiPost('/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    public function test_user_can_logout()
    {
        $response = $this->apiGet('/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_fetch_authenticated_user_data()
    {
        $response = $this->apiGet('/me');

        $response->assertStatus(200)
            ->assertJson([
                'id' => auth()->user()->id,
                'email' => auth()->user()->email,
            ]);
    }

    public function test_login_requires_email_and_password()
    {
        $response = $this->apiPost('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
