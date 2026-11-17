<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\RealEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (! Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo es inválido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->activo) {
                Auth::logout();

                return back()->with('error', 'Tu cuenta se encuentra inactiva. Contacta con el administrador.');
            }

            $request->session()->regenerate();

            if (! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('warning', 'Debes verificar tu correo electrónico antes de acceder a la plataforma.');
            }

            return $this->redirectBasedOnRole($user)->with('success', '¡Bienvenido(a) a GraCitas, '.$user->name.'!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            if (! Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email:rfc', 'max:150', 'unique:users', new RealEmailDomain],
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:cliente,propietario',
        ], [
            'name.required' => 'Tu nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico es inválido.',
            'email.unique' => 'Este correo ya se encuentra registrado en GraCitas.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'password' => Hash::make($validated['password']),
            'rol' => $validated['rol'],
            'activo' => 1,
        ]);

        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            Log::error('Error al enviar correo de verificación inicial: '.$e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', '¡Tu cuenta ha sido creada exitosamente! Te enviamos un correo de verificación a '.$user->email.'.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Has cerrado sesión correctamente.');
    }

    public function profile()
    {
        $user = Auth::user();

        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email:rfc', 'max:150', 'unique:users,email,'.$user->id, new RealEmailDomain],
            'telefono' => 'nullable|string|max:20',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (! empty($validated['current_password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
        }

        $emailChanged = $user->email !== $validated['email'];

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->telefono = $validated['telefono'] ?? null;

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                Log::error('Error al enviar correo de verificación tras cambio de email: '.$e->getMessage());
            }

            return redirect()->route('verification.notice')
                ->with('warning', 'Has actualizado tu dirección de correo electrónico. Te hemos enviado un enlace de confirmación a tu nueva bandeja.');
        }

        return back()->with('success', 'Perfil actualizado satisfactoriamente.');
    }

    public function destroyProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return back()->with('error', 'Las cuentas de administrador no pueden ser eliminadas desde el perfil.');
        }

        $request->validate([
            'password_delete' => 'required|string',
        ], [
            'password_delete.required' => 'Debes ingresar tu contraseña actual para confirmar la eliminación.',
        ]);

        if (! Hash::check($request->password_delete, $user->password)) {
            return back()->withErrors(['password_delete' => 'La contraseña ingresada no es correcta.']);
        }

        if ($user->hasActiveReservations()) {
            $mensaje = $user->isPropietario()
                ? 'No puedes eliminar tu cuenta porque tienes reservas confirmadas programadas en tus canchas. Debes cancelarlas o atenderlas antes de eliminar tu perfil.'
                : 'No puedes eliminar tu cuenta porque tienes reservas confirmadas pendientes. Debes cancelarlas antes de eliminar tu cuenta.';

            return back()->with('error', $mensaje);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->deleteWithRelations();

        return redirect()->route('home')
            ->with('success', 'Tu cuenta y datos asociados han sido eliminados permanentemente del sistema GraCitas.');
    }

    private function redirectBasedOnRole(User $user)
    {
        return match ($user->rol) {
            'admin' => redirect()->route('admin.dashboard'),
            'propietario' => redirect()->route('propietario.dashboard'),
            default => redirect()->route('cliente.reservas'),
        };
    }
}
