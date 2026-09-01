<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

class LanguageController extends Controller
{
    /**
     * Switch application locale between English and Swahili.
     *
     * @param Request $request
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, string $locale)
    {
        $supportedLocales = ['en', 'sw'];

        if (!in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale', 'en');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        // If authenticated user and column exists, save preference
        if (auth()->check()) {
            $user = auth()->user();
            if (isset($user->preferred_language) || Schema::hasColumn('users', 'preferred_language')) {
                $user->preferred_language = $locale;
                $user->save();
            }
        }

        $cookie = cookie()->forever('locale', $locale);

        return redirect()->back()->withCookie($cookie);
    }
}
