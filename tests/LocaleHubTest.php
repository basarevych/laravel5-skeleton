<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocaleHubTest extends TestCase
{
//    use WithoutMiddleware;

    /**
     * Set up test
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Config::set('app.available_locales', [ 'en', 'ru', 'fr' ]);
        Config::set('locale', 'en');
    }

    /**
     * Test locale autodetection
     *
     * @return void
     */
    public function testLocaleAutodetection()
    {
        $service = \Mockery::mock("App\Services\LocaleHub[setLocale]");

        $service->shouldReceive('setLocale')
                ->once()
                ->with('fr');

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "en;q=0.4,de,fr;q=0.9,ru;q=0.5";
        $service->detectLocale();
    }
}
