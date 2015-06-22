<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Extract token from HTML string
     *
     * @param string $content
     * @return string
     */
    public function getToken($content)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($content);

        $xpath = new \DOMXPath($dom);
        $tags = $xpath->query('//input[@name="_token"]');
        $token = $tags->item(0)->getAttribute('value');
        return $token;
    }
}
