<?php

namespace App\Services;

use App;
use Cookie;

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
        $cookie = @$_COOKIE['locale'];

        if ($cookie && in_array($cookie, $this->available)) {
            $locale = $cookie;
        } else {
            $header = str_replace(" ", "", @$_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $languages = [];
            foreach (explode(",", $header) as $language) {
                $parts = explode(";", $language);
                if (count($parts) == 1) {
                    $code = $parts[0];
                    $priority = 1.0;
                } else if (substr($parts[0], 0, 2) == 'q=') {
                    $code = $parts[1];
                    $priority = substr($parts[0], 2);
                } else if (substr($parts[1], 0, 2) == 'q=') {
                    $code = $parts[0];
                    $priority = substr($parts[1], 2);
                } else {
                    continue;
                }

                $languages[$code] = (float)$priority;
            }

            arsort($languages);

            foreach ($languages as $code => $priority) {
                $test = \Locale::lookup($this->available, $code);
                if ($test && in_array($test, $this->available)) {
                    $locale = $test;
                    break;
                }
            }
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
        App::setLocale($locale);
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
        $fmt = new \NumberFormatter($this->current, \NumberFormatter::DECIMAL);
        $parse = $fmt->parse($value);
        if ($parse !== false)
            return $parse;

        return $value;
    }
}
