<?php

namespace Tests\Feature;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    /**
     * Verifica que el idioma por defecto configurado sea español ('es').
     */
    public function test_default_locale_is_spanish(): void
    {
        $this->assertSame('es', config('app.locale'));
    }

    /**
     * Verifica que el usuario pueda cambiar de idioma a inglés ('en').
     */
    public function test_can_switch_locale_to_english(): void
    {
        $response = $this->get(route('locale.switch', 'en'));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('locale', 'en');
    }

    /**
     * Verifica que el usuario pueda cambiar de idioma a francés ('fr').
     */
    public function test_can_switch_locale_to_french(): void
    {
        $response = $this->get(route('locale.switch', 'fr'));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
        $response->assertCookie('locale', 'fr');
    }

    /**
     * Verifica que un idioma no soportado sea ignorado de manera segura.
     */
    public function test_unsupported_locale_is_ignored(): void
    {
        $response = $this->get(route('locale.switch', 'de'));

        $response->assertRedirect();
        $response->assertSessionMissing('locale');
    }

    /**
     * Verifica que el middleware SetLocale aplique el idioma de la sesión.
     */
    public function test_set_locale_middleware_applies_session_locale(): void
    {
        $middleware = new SetLocale;

        $requestEn = Request::create('/');
        $requestEn->setLaravelSession(session()->driver());
        $requestEn->session()->put('locale', 'en');

        $middleware->handle($requestEn, function () {
            $this->assertSame('en', app()->getLocale());

            return response('OK');
        });

        $requestFr = Request::create('/');
        $requestFr->setLaravelSession(session()->driver());
        $requestFr->session()->put('locale', 'fr');

        $middleware->handle($requestFr, function () {
            $this->assertSame('fr', app()->getLocale());

            return response('OK');
        });
    }

    /**
     * Verifica que las traducciones existan en español, inglés y francés.
     */
    public function test_translations_exist_for_supported_languages(): void
    {
        $this->assertSame('Explorar Canchas', __('messages.nav.explore', [], 'es'));
        $this->assertSame('Explore Pitches', __('messages.nav.explore', [], 'en'));
        $this->assertSame('Explorer les Terrains', __('messages.nav.explore', [], 'fr'));

        $this->assertSame('Iniciar Sesión', __('messages.nav.login', [], 'es'));
        $this->assertSame('Log In', __('messages.nav.login', [], 'en'));
        $this->assertSame('Connexion', __('messages.nav.login', [], 'fr'));
    }
}
