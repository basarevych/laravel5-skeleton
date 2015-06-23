<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Repositories\Users', 'App\Repositories\Users');
        $this->app->bind('App\Contracts\Repositories\PasswordResets', 'App\Repositories\PasswordResets');
    }
}