<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Locales permitidos en la aplicación.
     *
     * @var array<int, string>
     */
    public const SUPPORTED_LOCALES = ['es', 'en', 'fr'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! $locale && $request->hasCookie('locale')) {
            $locale = (string) $request->cookie('locale');
        }

        if (! $locale || ! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = (string) config('app.locale', 'es');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
