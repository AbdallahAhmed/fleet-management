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

Route::get('/shows/{id}', 'ShowsController@getShow');
Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function ($router) {
    $router->any('/', 'AuthController@login')->middleware('guest')->name('login');
    $router->any('/register', 'AuthController@register')->middleware('guest')->name('register');
    Route::group(['middleware' => 'auth'], function ($router) {
        $router->post('/logout', 'AuthController@logout')->name('logout');
        $router->get('/dashboard', 'HomeController@index')->name('home');
        $router->get('/trips', 'TripsController@index')->name('trips.index');
        $router->any('/trips/create', 'TripsController@store')->name('trips.create');
        $router->get('/trips/{id}/show', 'TripsController@show')->name('trips.show');
        $router->post('/trips/{id}/book', 'BookingsController@store')->name('trips.book.store');
        $router->get('/trips/{id}/book', 'BookingsController@create')->name('trips.book');
        $router->post('/trips/{id}/check', 'BookingsController@checkAvailable')->name('trips.check');
    });
});