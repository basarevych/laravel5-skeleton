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
    Route::get('login-form', [ 'as' => 'login', 'uses' => 'Auth\AuthController@getLoginForm' ]);
    Route::post('login-form', 'Auth\AuthController@postLoginForm');
    Route::post('validate-login-form', 'Auth\AuthController@postValidateLoginForm');

    // User logout route
    Route::get('logout', [ 'as' => 'logout', 'uses' => 'Auth\AuthController@getLogout' ]);

    // Password reset request routes
    Route::get('reset-request-form', [ 'as' => 'reset-request', 'uses' => 'Auth\PasswordController@getResetRequestForm' ]);
    Route::post('reset-request-form', 'Auth\PasswordController@postResetRequestForm');
    Route::post('validate-request-form', 'Auth\PasswordController@postValidateRequestForm');

    // Password reset confirmation routes
    Route::get('reset-confirm/{token}', [ 'as' => 'reset-confirm', 'uses' => 'Auth\PasswordController@getResetConfirm' ]);
    Route::get('reset-confirm-form/{token}', 'Auth\PasswordController@getResetConfirmForm');
    Route::post('reset-confirm-form', 'Auth\PasswordController@postResetConfirmForm');
    Route::post('validate-confirm-form', 'Auth\PasswordController@postValidateConfirmForm');

    // User registration routes
    Route::get('registration/{token}', [ 'as' => 'register', 'uses' => 'Auth\RegistrationController@getRegistration' ]);
    Route::get('registration-form', 'Auth\RegistrationController@getRegistrationForm');
    Route::post('registration-form', 'Auth\RegistrationController@postRegistrationForm');
    Route::post('validate-registration-form', 'Auth\RegistrationController@postValidateRegistrationForm');

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
    Route::post('user/validate-create-form', 'UserController@validateCreateForm');

});
