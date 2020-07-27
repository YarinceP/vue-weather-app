<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Zttp\Zttp;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/weather', function () {
    $lat = request('lat');
    $lon = request('lon');
    $apiKey = config('services.open_weather.key');
    $response = Http::get("https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&exclude=&appid=$apiKey&units=metric&lang=es");
    return $response->json();
});
