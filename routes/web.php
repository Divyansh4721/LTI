<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaunchController;
use App\Http\Controllers\LTIController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PlatformKeyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BooksControllerTest;
use Illuminate\Support\Facades\Log;

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
        Log::info('This is a test logging!');
        return view('welcome');
    });

    require __DIR__ . '/auth.php';

    Auth::routes(['register' => false]);

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Auth Middleware
    Route::group(['middleware' => 'auth'], function () {

    //Manage platform
    Route::get('dashboard', [PlatformController::class, 'index'])->name('platform.index');
    Route::get('changeStatus', [PlatformController::class, 'changeStatus'])->name('changeStatus');
    Route::get('create_lti_platform', [PlatformController::class, 'show']);
    Route::post('create_lti_platform', [PlatformController::class, 'store']);
    Route::get('edit_lti_platform/{platformData}', [PlatformController::class, 'edit']);
    Route::post('edit_lti_platform/{platformData}', [PlatformController::class, 'update'])->name('edit_lti_platform');
    Route::get('delete_platform/{id}', [PlatformController::class, 'delete'])->name('delete_lti_platform');
    
    //manage platform Keys
    Route::post('create_lti_key', [PlatformKeyController::class, 'store']);
    Route::get('create_lti_key/{id}', [PlatformKeyController::class, 'platformKeys']);
    Route::get('create_lti_platform_keys/{id}', [PlatformKeyController::class, 'createPlatformKeys']);
    Route::get('edit_lti_platform_key/{id}', [PlatformKeyController::class, 'edit']);
    Route::post('edit_lti_platform_key/{id}', [PlatformKeyController::class, 'update'])->name('edit_lti_platform_key');
    Route::get('delete_lti_platform_key/{id}', [PlatformKeyController::class, 'delete'])->name('delete_lti_platform_key');
    
});

    /**
     *  Manage the launch
     *  Creating connection and authentication with IMSGlobal protocal.
     */
    Route::any('ltilaunch', [LaunchController::class, 'launch'])->name('launch');
    Route::post('/launch-content', [LaunchController::class, 'apiResponse'])->name('launch-content');
    // Route::any('/autoSuggest', [LaunchController::class, 'autoSuggest'])->name('auto-suggest'); //parent_id null
    
    // Route::any('ltilaunchresource/{asset_id}', [LaunchController::class, 'launchResource'])->name('launch-resource');
    Route::any('ltilaunch/oidclogin', [LaunchController::class, 'oidc']);
    Route::get('ltilaunch/jwks/{client_id?}', [LaunchController::class, 'jwk']);
    Route::post('ltilaunch/postresponsedata', [LaunchController::class, 'postResponseData']);
     
    Route::get('/booklist', [LaunchController::class, 'book']);


    /**  Books Content load using API's */
    Route::get('/books2', [BooksControllerTest::class, 'index'])->name('books-content'); //parent_id null
    Route::get('/books-content', [BooksControllerTest::class, 'index']); //parent_id null