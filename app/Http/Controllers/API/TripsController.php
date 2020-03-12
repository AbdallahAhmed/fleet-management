<?php

namespace App\Http\Controllers\API;

use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TripsController extends ApiController
{
    /**
     * POST /trips/list
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tripsListing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_station' => 'required',
            'end_station' => 'required',
            'date' => 'date_format:Y-m-d'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(($validator->errors()->all()));
        }

        $source_id = +$request->get('start_station');
        $destination_id = +$request->get('end_station');

        //checking for trips within the source and the destination cities
        $trips = Trip::where([
            ['source_id', '<=', $source_id],
            ['destination_id', '>=', $destination_id],
        ])->get();

        //returning only trips with available seats
        $trips = $trips->filter(function ($trip) use ($destination_id) {
            $count = $trip->bookings()->where([
                ['destination_id', '>=', $destination_id],
                ['source_id', '<=', $destination_id]
                ])->count();
            $trip->available_seats = 12 - $count;
            return $count < 12;
        });
        if (count($trips))
            return $this->response($trips);

        return $this->errorResponse(["message" => 'Sorry, no available seats right now .. Please try again later!']);
    }
}
