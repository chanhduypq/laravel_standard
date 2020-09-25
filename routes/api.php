<?php

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'prefix' => 'auth'
], function () {
//    Route::post('login', 'AuthController@login');
//    Route::post('signup', 'AuthController@signup');

    Route::group([
      'middleware' => 'auth:sanctum'
    ], function() {
//        Route::get('logout', 'AuthController@logout');
//        Route::get('user', 'AuthController@user');
    });
});

Route::group([
    'prefix' => 'classes'
], function () {
    Route::group([
      'middleware' => 'auth:sanctum'
    ], function() {
//        Route::delete('{id}', 'ClassApiController@delete');
//        Route::post('add', 'ClassApiController@add');
//        Route::put('edit/{id}', 'ClassApiController@edit');
    });
//    Route::get('', 'ClassApiController@index');
//    Route::get('{id}', 'ClassApiController@get');

});

Route::group([
    'prefix' => 'students'
], function () {
    Route::group([
      'middleware' => 'auth:sanctum'
    ], function() {
//        Route::delete('{id}', 'StudentApiController@delete');
//        Route::post('add', 'StudentApiController@add');
//        Route::put('edit/{id}', 'StudentApiController@edit');
//        Route::patch('editone/{id}', 'StudentApiController@editone');
    });
//    Route::get('', 'StudentApiController@index');
//    Route::get('{id}', 'StudentApiController@get');

});


//Route::middleware('auth:sanctum')->get('/book', 'BookController@index');
//Route::get('/book', 'BookController@index');


