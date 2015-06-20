<?php

namespace Test\Services;

use TestCase;
use Config;
use LocaleHub;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocaleHubTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Config::set('app.available_locales', [ 'en', 'ru', 'fr' ]);
        Config::set('locale', 'en');

        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "en;q=0.4,de,fr-FR;q=0.9,ru;q=0.5";
    }

    public function testLocaleAutodetection()
    {
        $service = \Mockery::mock("App\Services\LocaleHub[setLocale]");

        $service->shouldReceive('setLocale')
                ->once()
                ->with('fr');

        $service->detectLocale();
    }

    public function testLocaleAutodetectionPrefersCookie()
    {
        $service = \Mockery::mock("App\Services\LocaleHub[setLocale]");

        $service->shouldReceive('setLocale')
                ->once()
                ->with('ru');

        $_COOKIE['locale'] = 'ru';
        $service->detectLocale();
    }

    public function testFormatNumber()
    {
        \LocaleHub::setLocale('en');
        $this->assertEquals("9,000.42", \LocaleHub::formatNumber(9000.42, 2));
    }

    public function testParseNumber()
    {
        \LocaleHub::setLocale('en');
        $this->assertEquals(9000.42, \LocaleHub::parseNumber("9,000.42"));
    }
}
