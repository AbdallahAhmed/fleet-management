<?php

use App\City;
use App\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @param null $days
     * @return void
     */
    public function run($days = null)
    {
        $cities = City::pluck('id')->toArray();

        for ($i = 0; $i < (isset($days) ? 1 : 3); $i++) {
            for ($j = 0; $j < 3; $j++) {
                $start = rand($cities[0], $cities[(count($cities) - 1) / 2]);
                $end = rand($start, $cities[count($cities) - 1]);
                Trip::create([
                    'source_id' => $start,
                    'destination_id' => $end,
                    'date_to_book' => Carbon::now()->addDays(isset($days) ? $days :$j)->format('Y-m-d'),
                ]);
            }
        }
    }
}
