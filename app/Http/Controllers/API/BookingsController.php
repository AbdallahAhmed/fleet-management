<?php

namespace App\Http\Controllers\API;

use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingsController extends ApiController
{
    /**
     * POST /trips/book
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_station' => 'required',
            'end_station' => 'required',
            'trip_id' => 'required',
            'date' => 'date_format:Y-m-d'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(($validator->errors()->all()));
        }
        $source_id = +$request->get('start_station');
        $destination_id = +$request->get('end_station');
        $trip_id = +$request->get('trip_id');
        $user_id = Auth::guard('api')->id();
        $booked = Booking::where([
            ['trip_id', '=', $trip_id],
            ['destination_id', '>=', $destination_id],
            ['source_id', '<=', $destination_id]
        ])->count();
        if ($booked < 12) {
            $booking = new Booking();
            $booking->source_id = $source_id;
            $booking->destination_id = $destination_id;
            $booking->trip_id = $trip_id;
            $booking->user_id = $user_id;
            $booking->seat_no = Str::random(8);
            $booking->save();
            return $this->response(["book" =>$booking->load(['source', 'destination'])]);
        }
        return $this->errorResponse(['message' => 'Sorry, no available seats right now .. Please try again later!']);
    }

}
