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
        $trip_seeder = new \TripSeeder();
        $trip_seeder->run(0);
    }
}
