<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api'], function($api){
        // make mushroom API endpoints only for index and store requests
        $api->resource('mushrooms', 'MushroomController', ['only' => ['index', 'store']]);
        $api->get('guzzle', 'MushroomController@sendRequestToAzure');
    });
});