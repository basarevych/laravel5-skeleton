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

// Authentication routes
Route::group([ 'prefix' => 'auth', 'as' => 'auth.' ], function () {

    Route::get('login-form', [ 'as' => 'login', 'uses' => 'AuthController@getLoginForm' ]);
    Route::post('login-form', 'AuthController@postLoginForm');
    Route::post('validate-login-form', 'AuthController@postValidateLoginForm');

    Route::get('logout', [ 'as' => 'logout', 'uses' => 'AuthController@getLogout' ]);

});

// These routes require authenticated user
Route::group([ 'middleware' => 'auth' ], function () {

    Route::get('profile-form', [ 'as' => 'profile', 'uses' => 'ProfileController@getProfileForm' ]);
    Route::post('profile-form', 'ProfileController@postProfileForm');
    Route::post('validate-profile-form', 'ProfileController@postValidateProfileForm');

});

// Administrator routes
Route::group([ 'middleware' => 'admin' ], function () {

    Route::resource('user', 'UserController');

});
