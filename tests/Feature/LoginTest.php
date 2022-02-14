<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testRequiresEmailAndLogin()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]);
    }

    public function testUserLoginsSuccessfully()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('test123123'),
        ]);

        $payload = ['email' => 'test@test.com', 'password' => 'test123123'];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token'
            ]);

    }
}
