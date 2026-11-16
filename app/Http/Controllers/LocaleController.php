<?php

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Cambiar el idioma activo de la aplicación.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, SetLocale::SUPPORTED_LOCALES, true)) {
            $request->session()->put('locale', $locale);

            return redirect()
                ->back(fallback: route('home'))
                ->withCookie(cookie()->forever('locale', $locale));
        }

        return redirect()->back(fallback: route('home'));
    }
}
