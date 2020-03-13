<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public $data = array();

    public function bookings($trip_id)
    {
        $trip = Trip::findOrFail($trip_id);
        $this->data['trip'] = $trip;
        $this->data['bookings'] = $trip->bookings()->filter(function ($booking) {
            $booking->load(['source', 'destination']);
            return true;
        });

        dd($this->data['bookings']);
    }
}
