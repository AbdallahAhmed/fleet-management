<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', 'API\AuthController@login');
Route::post('/auth/register', 'API\AuthController@register');

Route::middleware('jwt')->group(function (){
    Route::post('/update', 'API\AuthController@updateAccount');

    Route::get('/favorites', 'API\UserController@getFavorites');
    Route::post('/addFavorite/{id}', 'API\UserController@addFavorite');
    Route::post('/removeFavorite/{id}', 'API\UserController@removeFavorite');
});
//Route::post('auth/chec')
/*Route::group(["middleware" => ['auth']], function ($router) {
    Route::group(["prefix" => 'trips'], function ($router) {
        $router->post('/cities', 'API\TripsController@cities');
        $router->post('/list', 'API\TripsController@tripsListing');
        $router->post('/book', 'API\BookingsController@store');
    });
});*/

