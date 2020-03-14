<?php

namespace App\Http\Controllers\Admin;

use App\Booking;
use App\Http\Controllers\Controller;
use App\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $data = array();

    /**
     * GET /admin/dashboard
     * @route home
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->data["trips"] = Trip::opened()->count();
        $this->data["bookings"] = Booking::opened()->count();
        return view('admin.dashboard', $this->data);
    }
}
