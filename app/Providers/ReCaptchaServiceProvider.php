<?php

namespace App\Providers;

use Validator;
use ReCaptcha;

use Illuminate\Support\ServiceProvider;
use App\Services\ReCaptcha as ReCaptchaService;

class ReCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('recaptcha', function($attribute, $value, $parameters) {
            return ReCaptcha::verify($value);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('recaptcha', function ($app)
        {
            return new ReCaptchaService();
        });
    }
}
