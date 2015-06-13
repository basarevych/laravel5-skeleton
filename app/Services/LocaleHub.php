<?php

namespace App\Services;

use Lang;
use Aura\Accept\AcceptFactory;

class LocaleHub
{
    /**
     * Available locales
     *
     * @var array
     */
    protected $available = [];

    /**
     * Current locale
     *
     * @var string
     */
    protected $current = null;

    /**
     * Constructor
     *
     * @return Locale
     */
    public function __construct()
    {
        $this->available = config('app.available_locales');
        $this->current = config('app.locale');
    }

    /**
     * Get number of available locales
     *
     * @return integer
     */
    public function countAvailableLocales()
    {
        return count($this->available);
    }

    /**
     * Get array of available locales
     *
     * @return array
     */
    public function getAvailableLocales()
    {
        return $this->available;
    }

    /**
     * Detect and set locale using HTTP_ACCEPT_LANGUAGE
     *
     * @return LocaleHub
     */
    public function detectLocale()
    {
        $locale = config('app.locale');

        if (@$_COOKIE['locale'] && in_array($_COOKIE['locale'], $this->available)) {
            $locale = $_COOKIE['locale'];
        } else {
            $acceptFactory = new AcceptFactory($_SERVER);
            $accept = $acceptFactory->newInstance();
            $language = $accept->negotiateLanguage($this->available);
            if ($language)
                $locale = $language->getValue();
        }

        $this->setLocale($locale);
    }

    /**
     * Set current locale
     *
     * @param string $locale
     * @return LocaleHub
     */
    public function setLocale($locale)
    {
        if (!in_array($locale, $this->available))
            throw new \Exception("Non-supported locale: $locale");

        locale_set_default($locale);
        setlocale(LC_ALL, $locale . '.UTF-8');
        Lang::setLocale($locale);
        $this->current = $locale;

        return $this;
    }

    /**
     * Get current locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->current;
    }

    /**
     * Convert number to locale string
     *
     * @param   integer $number
     * @param   integer $fractionDigits
     * @return  string
     */
    public function formatNumber($number, $fractionDigits = 0)
    {
        if ($number === null)
            return '';

        $fmt = new \NumberFormatter($this->current, \NumberFormatter::DECIMAL);
        $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $fractionDigits);
        return $fmt->format($number);
    }

    /**
     * Convert Locale-formatted string to a number
     *
     * @param  string $value
     * @return mixed
     */
    public function parseNumber($value)
    {
        $fmt = new NumberFormatter($this->current, NumberFormatter::DECIMAL);
        $parse = $fmt->parse($value);
        if ($parse !== false)
            return $parse;

        return $value;
    }
}
