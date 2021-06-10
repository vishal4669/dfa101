<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/RegisterNewDF', '\App\Http\Controllers\API\UserController@register');
Route::post('/LoginDF', '\App\Http\Controllers\API\UserController@login');
Route::post('/ForgotPassword', '\App\Http\Controllers\API\UserController@forgotPassword');

Route::post('/ApproveAccount', '\App\Http\Controllers\API\UserController@approveAccount');
Route::post('/SetAccountDetails', '\App\Http\Controllers\API\UserController@setAccountDetails');
Route::post('/SetAccountPassword', '\App\Http\Controllers\API\UserController@setAccountPassword');

