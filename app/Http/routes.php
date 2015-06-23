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

    // User login routes
    Route::get('login-form', [ 'as' => 'login', 'uses' => 'AuthController@getLoginForm' ]);
    Route::post('login-form', 'AuthController@postLoginForm');
    Route::post('validate-login-form', 'AuthController@postValidateLoginForm');

    // User logout route
    Route::get('logout', [ 'as' => 'logout', 'uses' => 'AuthController@getLogout' ]);

    // Password reset link request routes
    Route::get('reset-request-form', [ 'as' => 'reset-request', 'uses' => 'PasswordController@getResetRequestForm' ]);
    Route::post('reset-request-form', 'PasswordController@postResetRequestForm');
    Route::post('validate-request-form', 'PasswordController@postValidateRequestForm');

    // Password reset routes
    Route::get('reset-confirm-form/{token}', [ 'as' => 'reset-confirm', 'uses' => 'PasswordController@getResetConfirmForm' ]);
    Route::post('reset-confirm-form', 'PasswordController@postResetConfirmForm');
    Route::post('validate-confirm-form', 'PasswordController@postValidateConfirmForm');

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
