<?php

use App\Trip;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $trips = Trip::where([
        ['city_from_id', '<=', 6],
        /*['available_seats', '>', 0]*/
    ])->get()->pluck('id')->toArray();
    dd($trips);
    return view('welcome');
});
