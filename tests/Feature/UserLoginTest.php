<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{

    public function testInvalidLogin()
    {
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'garbage@email.com',
            'password' => 'garbage'
        ]);
        $response->assertStatus(400)
            ->assertJsonStructure([
                'errors' => [
                    'Credentials'
                ]
            ]);
    }


    public function testLoginSuccessfully()
    {
        User::where('email', 'testlogin@user.com')->delete();
        User::create([
            'email' => 'testlogin@user.com',
            'name' => 'anonymous',
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'testlogin@user.com',
            'password' => '123456'
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id', 'email', 'api_token'
                    ],
                    'token'
                ]
            ]);
    }
}
