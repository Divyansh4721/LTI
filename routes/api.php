<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Password;

use App\Http\Controllers\API\AuthenticationAPIController;
use App\Http\Controllers\API\ClientAPIController;
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

//----------------User Login Authontication API--------------//
Route::post('login', [AuthenticationAPIController::class, 'login']);

//----------------User Forgot- Password API------------------//
Route::post('forgot-password', [AuthenticationAPIController::class, 'password_reset_link']);


//----------------User After Login API's---------------------//

Route::group(['middleware' => 'auth:api'], function(){

//---------------User Dashboard API--------------------------//
Route::get('dashboard', [AuthenticationAPIController::class, 'dashboard']);

//---------------User Logout API----------------------------//
Route::post('logout', [AuthenticationAPIController::class, 'logout']);



//---------------Clients Routes Start Here.---------------------//
Route::get('client-list', [ClientAPIController::class, 'index']);
Route::post('add-client', [ClientAPIController::class, 'store']);
Route::get('client/{id}', [ClientAPIController::class, 'show']);
Route::patch('edit-client/{id}', [ClientAPIController::class, 'update']);
Route::delete('delete-client/{id}', [ClientAPIController::class, 'destroy']);
Route::get('trashed-client-list', [ClientAPIController::class, 'trash']);
Route::get('restore-client/{id}', [ClientAPIController::class, 'restore']);
//---------------Products Routes Ends Here.---------------------//

});
