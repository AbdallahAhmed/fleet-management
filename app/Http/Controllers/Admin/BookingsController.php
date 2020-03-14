<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\City;
use App\Http\Controllers\Controller;
use App\Trip;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;

class BookingsController extends Controller
{
    public $data = array();

    public $trip;

    public function __construct()
    {
        $this->trip = Trip::opened()->where('id', +Route::current()->parameter('id'))->first();
        if (!$this->trip)
            abort('404');
    }

    /**
     * POST /admin/trips/{id}/book
     * @route trips.book.store
     * @param Request $request
     * @return Redirect
     */
    public function store(Request $request, $id)
    {
        $users = User::pluck('id')->toArray();
        $validator = Validator::make($request->all(), [
            'user' => 'required|in:' . implode(",", $users)
        ], [
            'user.in' => "Invalid User"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $source_id = $request->get('source');
        $destination_id = $request->get('destination');
        $booking = new Booking();
        $booking->trip_id = $id;
        $booking->source_id = $source_id;
        $booking->destination_id = $destination_id;
        $booking->user_id = $request->get('user');
        $booking->seat_no = Str::random(8);
        $booking->save();
        return redirect()->route('trips.show', $id)->with('status', 'Booked Successfully');
    }

    /**
     * GET /admin/trips/{id}/book
     * @route trips.book
     * @param Integer id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id)
    {
        $this->data['trip'] = $this->trip;
        $this->data['cities'] = $this->trip->cities;
        $this->data['users'] = User::select(['id', 'email'])->get();
        return view('admin.trips.bookings.create', $this->data);
    }

    /**
     * POST /trips/{id}/check
     * @route trips.check
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function checkAvailable(Request $request, $id)
    {
        $this->trip = Trip::findOrFail($id);
        $cities = City::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $validator = Validator::make($request->all(), [
            'source' => 'required|in:' . implode(",", $cities),
            'destination' => 'required|in:' . implode(",", $cities) . '|different:source',
            'user' => 'required|in:' . implode(",", $users)
        ], [
            'source.in' => "Source city is unknown!",
            'destination.in' => "Destination city is unknown!",
            'destination.different' => 'You should choose different source and destination',
            'user.in' => "Invalid User"
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }
        $source_id = $request->get('source');
        $destination_id = $request->get('destination');
        $booked = $this->trip->bookings()->where([
            ['source_id', '<=', $source_id],
            ['destination_id', '>=', $source_id]
        ])->orWhere([
            ['source_id', '>=', $source_id],
            ['destination_id', '<=', $destination_id]
        ])->count();
        $available = $booked < 12 ? true : false;
        return response()->json(['available' => $available], 200);

    }

}
