<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $data = array();

    public function index(){
        $this->data["trips"] = Trip::opened()->count();
        $this->data["bookings"] = Booking::opened()->count();
        return view('admin.dashboard', $this->data);
    }
}
