<?php

namespace Tests\Feature;

use App\Booking;
use App\City;
use App\Trip;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class TripTest extends TestCase
{
    // user authentication
    public function testUnauthorizedUser()
    {
        $this->json('POST', '/api/trips/list')
            ->assertStatus(401);
    }

    // request for cities
    public function testCities(){
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('POST', '/api/trips/cities', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "cities"
                ]
            ]);
    }

    // listing available trips without sending the source and destination
    public function testListingTripsWithoutStations()
    {
        $faker = Faker::create();
        $user = factory(User::class)->create([
            'email' => $faker->email,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [

        ];
        $this->json('POST', '/api/trips/list', $payload, $headers)
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    0 => "The start station field is required.",
                    1 => "The end station field is required."
                ],
                "status" => false
            ]);
    }

    // listing available seats with source and destination with empty seats
    public function testAvailableSeats()
    {
        Booking::where('id', '>', '1')->delete();
        Trip::where('id', '>', '1')->delete();
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $cities_array = City::pluck('id')->toArray();
        for ($i = 0; $i < 3; $i++) {
            Trip::create([
                'source_id' => $cities_array[0],
                'destination_id' => $cities_array[count($cities_array) - 1],
                'date_to_book' => Carbon::now()->format('Y-m-d')
            ]);
        }
        $payload = [
            "start_station" => 3,
            "end_station" => 15
        ];
        $this->json('POST', '/api/trips/list', $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "available_trips"
                ]
            ]);
    }

    // listing available seats with source and destination with unavailable seats
    public function testUnavailableSeats()
    {
        Booking::where('id', '>', '1')->delete();
        Trip::where('id', '>', '1')->delete();
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $cities_array = City::pluck('id')->toArray();
        $trip = Trip::create([
            'source_id' => $cities_array[0],
            'destination_id' => $cities_array[count($cities_array) - 1],
            'date_to_book' => Carbon::now()->format('Y-m-d')
        ]);
        for ($i = 0; $i < 12; $i++) {
            Booking::create([
                'source_id' => 3,
                'destination_id' => 15,
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'seat_no' => Str::random(6)
            ]);
        }
        $payload = [
            "start_station" => 3,
            "end_station" => 15
        ];
        $this->json('POST', '/api/trips/list', $payload, $headers)
            ->assertStatus(200)
            ->assertExactJson([
                "data" => [
                    "message" => "Sorry, no available seats right now .. Please try again later!"
                ],
                "status" => true
            ]);
    }

    // booking a seat within an available trip
    public function testBookSeat()
    {
        Booking::where('id', '>', '1')->delete();
        Trip::where('id', '>', '1')->delete();
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $cities_array = City::pluck('id')->toArray();
        $trip = Trip::create([
            'source_id' => $cities_array[0],
            'destination_id' => $cities_array[count($cities_array) - 1],
            'date_to_book' => Carbon::now()->format('Y-m-d')
        ]);
        $payload = [
            "start_station" => 3,
            "end_station" => 15,
            "trip_id" => $trip->id
        ];
        $this->json('POST', '/api/trips/book', $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "book"
                ]
            ]);
    }

    // booking a seat for unknown trip
    public function testBookSeatForUnavailableTrip()
    {
        Booking::where('id', '>', '1')->delete();
        Trip::where('id', '>', '1')->delete();
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $cities_array = City::pluck('id')->toArray();
        $trip = Trip::create([
            'source_id' => $cities_array[0],
            'destination_id' => $cities_array[count($cities_array) - 1],
            'date_to_book' => Carbon::now()->format('Y-m-d')
        ]);
        $payload = [
            "start_station" => 3,
            "end_station" => 15,
            "trip_id" => 100
        ];
        $this->json('POST', '/api/trips/book', $payload, $headers)
            ->assertStatus(400)
            ->assertExactJson([
                "errors" => [
                    "message" => "Unavailable Trip!"
                ],
                "status" => false
            ]);
    }

    // listing available seats before the end of trip while other riders will left before the destination
    public function testAvailableSeatsBeforeEndOfTripDestination()
    {
        Booking::where('id', '>', '1')->delete();
        Trip::where('id', '>', '1')->delete();
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $cities_array = City::pluck('id')->toArray();
        $trip = Trip::create([
            'source_id' => $cities_array[0],
            'destination_id' => $cities_array[count($cities_array) - 1],
            'date_to_book' => Carbon::now()->format('Y-m-d')
        ]);
        for ($i = 0; $i < 12; $i++) {
            Booking::create([
                'source_id' => 1,
                'destination_id' => 15,
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'seat_no' => Str::random(6)
            ]);
        }
        $payload = [
            "start_station" => 15,
            "end_station" => 20
        ];
        $this->json('POST', '/api/trips/list', $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "available_trips",
                ]
            ]);
    }

    // booking a seat before the end of trip while other riders will left before the destination
    public function testBookSeatBeforeEndOfTripDestination()
    {
        Booking::where('id', '>', '1')->delete();
        Trip::where('id', '>', '1')->delete();
        $faker = Faker::create();
        $user = User::create([
            'email' => $faker->email,
            'name' => $faker->name,
            'password' => '123456',
            'api_token' => User::newApiToken()
        ]);
        $token = $user->api_token;
        $headers = ['Authorization' => "Bearer $token"];
        $cities_array = City::pluck('id')->toArray();
        $trip = Trip::create([
            'source_id' => $cities_array[0],
            'destination_id' => $cities_array[count($cities_array) - 1],
            'date_to_book' => Carbon::now()->format('Y-m-d')
        ]);
        for ($i = 0; $i < 12; $i++) {
            Booking::create([
                'source_id' => 1,
                'destination_id' => 15,
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'seat_no' => Str::random(6)
            ]);
        }
        $payload = [
            "start_station" => 15,
            "end_station" => 20,
            "trip_id" => $trip->id
        ];
        $this->json('POST', '/api/trips/book', $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "book"
                ]
            ]);
    }


}
