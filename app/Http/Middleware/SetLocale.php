<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetLocale — run on every web request.
 *
 * Resolution order:
 *   1. session('locale')   — set by the locale switcher (route locale.switch)
 *   2. cookie('locale')    — persistent fallback for guests / new sessions
 *   3. config('app.locale') — application default
 *
 * The chosen locale is applied with App::setLocale() and normalised back
 * into the session so it stays consistent for the rest of the visit.
 */
class SetLocale
{
    public const SUPPORTED = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->hasSession() ? $request->session()->get('locale') : null;

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = $request->cookie('locale');
        }

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale', 'ar');
        }

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        if ($request->hasSession() && $request->session()->get('locale') !== $locale) {
            $request->session()->put('locale', $locale);
        }

        return $next($request);
    }
}
