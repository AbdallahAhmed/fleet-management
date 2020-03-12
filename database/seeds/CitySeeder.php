<?php

use App\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();
        $cities = Config::get('constants.cities');
        foreach ($cities as $i => $city) {
            City::create([
                'name' => $city,
                'order' => $i + 1
            ]);
        }
    }
}
