<?php

namespace App\Services;

use App;
use ReCaptcha\ReCaptcha as ReCaptchaEngine;

class ReCaptcha
{
    /**
     * Get site key
     *
     * @return string|null
     */
    public function getSiteKey()
    {
        return config('recaptcha.site_key');
    }

    /**
     * Get secret
     *
     * @return string|null
     */
    public function getSecret()
    {
        return config('recaptcha.secret');
    }

    /**
     * Is ReCAPTCHA enabled (site_key and secret are provided)
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return ($this->getSiteKey() != null && $this->getSecret() != null);
    }

    /**
     * Get HTML/JS code
     *
     * @return string
     */
    public function getFormElement()
    {
        return '<div class="g-recaptcha" data-sitekey="' . $this->getSiteKey() . '"></div>'
            . '<script type="text/javascript"'
            . ' src="https://www.google.com/recaptcha/api.js?hl=' . App::getLocale() . '">'
            . '</script>';
    }

    /**
     * Verify user input
     *
     * @param string $input
     * @return boolean
     */
    public function verify($input)
    {
        $recaptcha = new ReCaptchaEngine($this->getSecret());
        return $recaptcha->verify($input, $_SERVER['REMOTE_ADDR']);
    }
}
