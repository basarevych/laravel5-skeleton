<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LocaleHub extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'locale_hub';
    }
}
