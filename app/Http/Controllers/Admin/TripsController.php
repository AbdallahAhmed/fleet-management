<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class TripsController extends Controller
{
    public $data = array();

    public function index(Request $request)
    {
        $this->data['source'] = null;
        $this->data['destination'] = null;
        $this->data['date'] = null;
        $query = new Trip();
        if ($request->filled('date')) {
            $this->data['date'] = $request->get('date');
            $query = $query->where('date_to_book', $request->get('date'));
        }
        if ($request->filled('source')) {
            $this->data['source'] = $request->get('source');
            $query = $query->where('source_id', $this->data['source']);
        }
        if ($request->filled('destination')) {
            $this->data['destination'] = $request->get('destination');
            $query = $query->where('destination_id', $this->data['destination']);
        }
        $trips = $query->get()->load(['source', 'destination']);
        $this->data['trips'] = $trips;
        $this->data['cities'] = City::all();
        return view('admin.trips.index', $this->data)->with($request->all());
    }

    public function store(Request $request)
    {
        if ($request->method() == "POST") {
            $cities = City::pluck('id')->toArray();
            $validator = Validator::make($request->all(), [
                'source' => 'required|in:' . implode(",", $cities),
                'destination' => 'required|in:' . implode(",", $cities) . '|different:source',
                'date' => 'nullable|date_format:Y-m-d'
            ], [
                'source.in' => "Source city is unknown!",
                'destination.in' => "Destination city is unknown!",
                'destination.different' => 'You should choose different source and destination'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }
            $date = $request->filled('date') ? Carbon::make($request->get('date'))->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $trip = new Trip();
            $trip->source_id = $request->get('source');
            $trip->destination_id = $request->get('destination');
            $trip->date_to_book = $date;
            $trip->save();
            return redirect()->route('trips.index')->with('status', "Trip Added Successfully");
        }
        $this->data['cities'] = City::all();
        return view('admin.trips.create', $this->data);

    }

    public function show($id)
    {
        $trip = Trip::findOrFail($id);
        $this->data['trip'] = $trip;
        $this->data['bookings'] = $trip->bookings;
        return view('admin.trips.show', $this->data);
    }
}
