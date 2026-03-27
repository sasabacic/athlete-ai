<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Register user - gets token back
        $response = $this->postJson('/api/signup', $userData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => ['id', 'name', 'email'],
                    'token'
                ]);

        $token = $response->json('token');

        // Use token to access protected endpoint
        $protectedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $protectedResponse->assertStatus(200);
    }

    public function test_user_can_login_and_use_token()
    {
        // Create user first
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Login - gets token back
        $loginData = [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'user' => ['id', 'name', 'email'],
                    'token'
                ]);

        $token = $response->json('token');

        // Now use token for authenticated requests
        // Example: Get user profile
        $profileResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');

        $profileResponse->assertStatus(200)
                      ->assertJson(['email' => 'jane@example.com']);

        // Example: Access training data (assuming you have this endpoint)
        // $trainingResponse = $this->withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        // ])->getJson('/api/trainings');
        // $trainingResponse->assertStatus(200);
    }

    public function test_unauthenticated_request_fails()
    {
        // Try to access protected endpoint without token
        $response = $this->getJson('/api/user');

        $response->assertStatus(401); // Unauthorized
    }

    public function test_logout_invalidates_token()
    {
        // Register and get token
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $registerResponse = $this->postJson('/api/signup', $userData);
        $token = $registerResponse->json('token');

        // Verify token works
        $beforeLogout = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');
        $beforeLogout->assertStatus(200);

        // Logout
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');
        $logoutResponse->assertStatus(204);

        // Try to use token after logout - should fail
        $afterLogout = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user');
        $afterLogout->assertStatus(401);
    }
}