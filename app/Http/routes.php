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

// Index page
Route::get('/', [ 'as' => 'index', function () { return view('welcome.index'); } ]);

Route::group([ 'prefix' => 'auth', 'as' => 'auth.' ], function () {

    // Authentication routes
    Route::get('login', [ 'as' => 'login', 'uses' => 'AuthController@getLogin' ]);
    Route::post('login', 'AuthController@postLogin');
    Route::get('logout', [ 'as' => 'logout', 'uses' => 'AuthController@getLogout' ]);

    // Registration routes. Uncomment to enable.
    Route::get('register', [ 'as' => 'register', 'uses' => 'AuthController@getRegister' ]);
    Route::post('register', 'AuthController@postRegister');
});

// These routes require authenticated user
Route::group([ 'middleware' => 'auth' ], function () {

    Route::resource('user', 'UserController');

});
