<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'sw'];

        $locale = Session::get('locale');

        if (!$locale && auth()->check() && !empty(auth()->user()->preferred_language)) {
            $locale = auth()->user()->preferred_language;
        }

        if (!$locale) {
            $locale = $request->cookie('locale');
        }

        if ($locale && in_array($locale, $supportedLocales, true)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
