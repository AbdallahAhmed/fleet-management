<?php

use App\City;
use App\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $base_station_id = City::select('id')->first()->id;
        $end_station_id = City::select('id')->orderBy('id', 'desc')->first()->id;

        for($i = 0; $i <= 2; $i++){
            Trip::create([
                'city_from_id' => $base_station_id,
                'city_to_id' => $end_station_id,
                'booking_date' => Carbon::now()->addDays($i)->format('Y-m-d'),
            ]);
        }
    }
}
