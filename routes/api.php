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

Route::get('checkmail', 'UserController@checkmail');



// User Routes
Route::post('authenticate', 'UserController@authenticate');
Route::middleware('jwt.auth')->get('user', 'UserController@index');
Route::middleware('jwt.auth')->get('user/{id}', 'UserController@show');
Route::get('userwithouttoken/{id}', 'UserController@show');
Route::middleware('jwt.auth')->post('user/{id}', 'UserController@update');
Route::middleware('jwt.auth')->get('getAgentList', 'UserController@getAllAgent');
Route::middleware('jwt.auth')->post('createAgent', 'UserController@store');
Route::middleware('jwt.auth')->get('currentUser', 'UserController@currentUser');
Route::middleware('jwt.auth')->post('changePassword/{id}', 'UserController@changePassword');
Route::post('forgotpassword', 'UserController@forgotPassword');

// Role Routes
Route::middleware('jwt.auth')->get('role', 'RoleController@index');
Route::middleware('jwt.auth')->post('role', 'RoleController@store');
Route::middleware('jwt.auth')->get('role/{id}', 'RoleController@show');

// Status Routes
Route::middleware('jwt.auth')->get('status', 'StatusController@index');
Route::middleware('jwt.auth')->post('status', 'StatusController@store');
Route::middleware('jwt.auth')->get('status/{id}', 'StatusController@show');


Route::middleware('jwt.auth')->post('application', 'ApplicationController@store');
Route::post('applicationSubmit', 'ApplicationController@applicationFormSubmit');
Route::post('linkUpdate', 'ApplicationController@updatelink');
Route::middleware('jwt.auth')->post('sendEmailLink', 'ApplicationController@sendLinkEmail');
Route::middleware('jwt.auth')->post('sendWhatsappLink', 'ApplicationController@sendLinkWhatsapp');
Route::get('linkDetail/{li}', 'ApplicationController@linkDetail');
Route::middleware('jwt.auth')->post('updateApplication/{id}', 'ApplicationController@update');
Route::post('editapplicationSubmit/{id}', 'ApplicationController@editapplicationFormSubmit');
Route::middleware('jwt.auth')->get('getApplication/{id}', 'ApplicationController@show');
Route::get('getApplicationwithouttoken/{id}', 'ApplicationController@show');
Route::middleware('jwt.auth')->get('getAllApplication', 'ApplicationController@getAllApplication');
Route::middleware('jwt.auth')->get('getPendingApplication', 'ApplicationController@getPendingApplication');
Route::middleware('jwt.auth')->get('getAmendApplication', 'ApplicationController@getAmendApplication');
Route::middleware('jwt.auth')->get('getDraftApplication', 'ApplicationController@getDraftApplication');
Route::middleware('jwt.auth')->get('getApprovedApplication', 'ApplicationController@getApprovedApplication');
Route::middleware('jwt.auth')->get('getRejectedApplication', 'ApplicationController@getRejectedApplication');
Route::middleware('jwt.auth')->get('getResubmitApplication', 'ApplicationController@getResubmitApplication');
Route::middleware('jwt.auth')->get('getUserAllApplication/{id}', 'ApplicationController@getUserAllApplication');
Route::middleware('jwt.auth')->get('getUserPendingApplication/{id}', 'ApplicationController@getUserPendingApplication');
Route::middleware('jwt.auth')->get('getUserAmendApplication/{id}', 'ApplicationController@getUserAmendApplication');
Route::middleware('jwt.auth')->get('getUserDraftApplication/{id}', 'ApplicationController@getUserDraftApplication');
Route::middleware('jwt.auth')->get('getUserApprovedApplication/{id}', 'ApplicationController@getUserApprovedApplication');
Route::middleware('jwt.auth')->get('getUserRejectedApplication/{id}', 'ApplicationController@getUserRejectedApplication');
Route::middleware('jwt.auth')->get('getUserReceivedApplication/{id}', 'ApplicationController@getUserReceivedApplication');
Route::middleware('jwt.auth')->get('getApplicationCount', 'ApplicationController@getApplicationCount');
Route::middleware('jwt.auth')->get('getUserApplicationCount/{id}', 'ApplicationController@getUserApplicationCount');

