<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{

    // missing register credentials
    public function testRegisterRequiresCredentials()
    {
        $response = $this->json('POST', 'api/auth/register');
        $response->assertStatus(400)
            ->assertJson([
                'errors' => [
                    0 => 'The email field is required.',
                    1 => 'The password field is required.',
                    2 => 'The name field is required.',
                ]
            ]);
    }

    // validation for password
    public function testRegisterMinimumPassword()
    {
        $faker = Faker::create();
        $email = $faker->email;
        $response = $this->json('POST', 'api/auth/register', [
            'email' => $email,
            'name' => 'Abdallah',
            'password' => '1234'
        ]);
        $response->assertStatus(400)
            ->assertJson([
                'errors' => [
                    0 => 'The password must be at least 6 characters.',
                ]
            ]);
    }

    //validation for unique email
    public function testRegisterTakenEmail()
    {
        $faker = Faker::create();
        $email = $faker->email;
        User::create([
            'name' => $faker->name,
            'email' => $email,
            'password' => '123456',
            'role' => 2
        ]);
        $response = $this->json('POST', 'api/auth/register', [
            'email' => $email,
            'name' => 'Abdallah',
            'password' => '123456'
        ]);
        $response->assertStatus(400)
            ->assertJson([
                'errors' => [
                    0 => 'The email has already been taken.',
                ]
            ]);
    }

    // register with correct data
    public function testRegisterSuccessfully(){
        $faker = Faker::create();
        $response = $this->json('POST', 'api/auth/register', [
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => $faker->password(6, 15)
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user'
                ]
            ]);
    }

}
