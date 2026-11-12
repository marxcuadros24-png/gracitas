<?php

namespace App\Http\Controllers;

use App\Rules\RealEmailDomain;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Muestra el formulario para solicitar el enlace de recuperación de contraseña.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace de restablecimiento al correo proporcionado.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email:rfc', new RealEmailDomain],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico con formato válido.',
        ]);

        try {
            $status = Password::broker()->sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', '¡Listo! Hemos enviado el enlace de restablecimiento a tu correo electrónico.');
            }

            return back()->withErrors(['email' => __($status)]);
        } catch (\Throwable $e) {
            Log::error('Error al enviar correo de recuperación de contraseña por Gmail/SMTP: '.$e->getMessage());

            return back()->with('error', 'No se pudo enviar el correo en este momento. Por favor verifica la configuración de Gmail en el archivo .env o intenta más tarde.');
        }
    }

    /**
     * Muestra el formulario para definir la nueva contraseña.
     */
    public function showResetForm(Request $request, ?string $token = null): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Restablece la contraseña del usuario.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'token.required' => 'El token de seguridad es inválido o ha expirado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', '¡Tu contraseña ha sido restablecida con éxito! Ya puedes iniciar sesión con tu nueva clave.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
