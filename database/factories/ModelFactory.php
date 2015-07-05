<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function ($faker) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->email,
        'password'          => $faker->password,
        'is_active'         => true,
        'is_admin'          => false,
        'remember_token'    => str_random(10),
    ];
});

$factory->defineAs(App\User::class, 'admin', function ($faker) use ($factory) {
    $user = $factory->raw('App\User');

    return array_merge($user, ['is_admin' => true]);
});

$factory->defineAs(App\Token::class, App\Token::TYPE_PASSWORD_RESET, function ($faker) {
    return [
        'type'  => App\Token::TYPE_PASSWORD_RESET,
        'token' => str_random(10),
    ];
});

$factory->defineAs(App\Token::class, App\Token::TYPE_REGISTRATION, function ($faker) {
    return [
        'type'  => App\Token::TYPE_REGISTRATION,
        'token' => str_random(10),
    ];
});
