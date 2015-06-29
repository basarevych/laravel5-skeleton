<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'auth/validate-login-form',
        'auth/validate-request-form',
        'auth/validate-confirm-form',
        'auth/validate-registration-form',
        'validate-profile-form',
    ];
}
