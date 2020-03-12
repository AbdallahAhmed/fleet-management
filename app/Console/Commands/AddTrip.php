<?php

namespace App\Console\Commands;

use App\City;
use App\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AddTrip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trip:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding new trip each day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $base_station_id = City::select('id')->first()->id;
        $end_station_id = City::select('id')->orderBy('id', 'desc')->first()->id;

        Trip::create([
            'city_from_id' => $base_station_id,
            'city_to_id' => $end_station_id,
            'booking_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
        ]);
    }
}
