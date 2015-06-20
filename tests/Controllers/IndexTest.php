<?php

namespace Test\Controllers;

use TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexTest extends TestCase
{
    public function testFrontPage()
    {
        $this->visit('/')
             ->see('Laravel 5');
    }
}
