<?php

use Illuminate\Http\Request;

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

//Test Routes
Route::middleware('jwt.auth')->get('test', 'UserController@test');

// User Routes
Route::post('authenticate', 'UserController@authenticate');
Route::middleware('jwt.auth')->get('user', 'UserController@index');
Route::middleware('jwt.auth')->get('user/{id}', 'UserController@show');
Route::middleware('jwt.auth')->get('getAgentList', 'UserController@getAllAgent');
Route::middleware('jwt.auth')->post('createAgent', 'UserController@store');

// Role Routes
Route::middleware('jwt.auth')->get('role', 'RoleController@index');
Route::middleware('jwt.auth')->post('role', 'RoleController@store');
Route::middleware('jwt.auth')->get('role/{id}', 'RoleController@show');

// Status Routes
Route::middleware('jwt.auth')->get('status', 'StatusController@index');
Route::middleware('jwt.auth')->post('status', 'StatusController@store');
Route::middleware('jwt.auth')->get('status/{id}', 'StatusController@show');


