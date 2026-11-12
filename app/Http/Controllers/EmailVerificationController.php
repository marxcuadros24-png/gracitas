<?php

namespace App\Http\Controllers;

use App\Rules\RealEmailDomain;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Muestra la pantalla avisando al usuario que debe verificar su correo.
     */
    public function notice(Request $request): View|RedirectResponse
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($request->user())
                ->with('info', 'Tu correo electrónico ya se encuentra verificado.');
        }

        return view('auth.verify-email');
    }

    /**
     * Procesa la verificación del correo con el enlace firmado.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($request->user())
                ->with('info', 'Tu correo electrónico ya había sido verificado previamente.');
        }

        $request->fulfill();

        return $this->redirectBasedOnRole($request->user())
            ->with('success', '¡Excelente! Tu correo electrónico ha sido verificado correctamente.');
    }

    /**
     * Reenvía el correo de verificación de cuenta.
     */
    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($request->user())
                ->with('info', 'Tu correo electrónico ya se encuentra verificado.');
        }

        try {
            $request->user()->sendEmailVerificationNotification();

            return back()->with('status', '¡Listo! Te hemos enviado un nuevo enlace de verificación a tu bandeja de entrada.');
        } catch (\Throwable $e) {
            Log::error('Error al reenviar correo de verificación por Gmail/SMTP: '.$e->getMessage());

            return back()->with('error', 'No se pudo enviar el correo de verificación. Verifica las credenciales de Gmail en el archivo .env o intenta más tarde.');
        }
    }

    /**
     * Permite al usuario corregir su dirección de correo si ingresó un correo equivocado.
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($user)
                ->with('info', 'Tu correo electrónico ya se encuentra verificado.');
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:150', 'unique:users,email,'.$user->id, new RealEmailDomain],
        ], [
            'email.required' => 'Debes ingresar una dirección de correo.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado por otro usuario.',
        ]);

        $user->email = $validated['email'];
        $user->save();

        try {
            $user->sendEmailVerificationNotification();

            return back()->with('status', '¡Correo actualizado correctamente a '.$user->email.'! Te hemos enviado un nuevo enlace de verificación.');
        } catch (\Throwable $e) {
            Log::error('Error al enviar correo tras corrección de email: '.$e->getMessage());

            return back()->with('error', 'El correo fue actualizado, pero ocurrió un problema al enviar el enlace. Intenta pulsar en "Reenviar Correo".');
        }
    }

    private function redirectBasedOnRole($user): RedirectResponse
    {
        return match ($user->rol) {
            'admin' => redirect()->route('admin.dashboard'),
            'propietario' => redirect()->route('propietario.dashboard'),
            default => redirect()->route('cliente.reservas'),
        };
    }
}
