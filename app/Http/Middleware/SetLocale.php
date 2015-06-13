<?php

namespace App\Http\Middleware;

use Closure;
use LocaleHub;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        LocaleHub::detectLocale();

        return $next($request);
    }
}
