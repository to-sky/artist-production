<?php

namespace App\Http\Middleware;

use Closure;
use Config;

class Locale {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->cookie('locale') ?? Config::get('app.locale');

        app()->setLocale($locale);

        $this->setGlobalLocale($locale);

        return $next($request);
    }

    /**
     * Set global locale
     *
     * @param $locale
     * @return string
     */
    public function setGlobalLocale($locale)
    {
        switch ($locale) {
            case 'ru':
                $locale = 'ru_RU.UTF-8';
                break;
            case 'de':
                $locale = 'de_DE.UTF-8';
                break;
            default:
                $locale = 'en_US.UTF-8';
        }

        setlocale(LC_TIME, $locale);

        return $locale;
    }
}
