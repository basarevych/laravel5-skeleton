<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LocaleHub;

class LocaleHubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('locale_hub', function ($app)
        {
            return new LocaleHub();
        });
    }
}
