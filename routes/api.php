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

Route::group(["middleware" => ['api-auth']], function ($router) {
    Route::group(["prefix" => 'trips'], function ($router) {
        $router->post('/book', 'API\BookingController@store');
    });
});

