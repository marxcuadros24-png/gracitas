<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('id', 'desc');

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telefono', 'like', "%{$q}%");
            });
        }

        $usuarios = $query->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:admin,propietario,cliente',
            'password' => 'required|string|min:6',
            'activo' => 'required|boolean',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Ya existe un usuario con este correo electrónico.',
            'password.min' => 'La contraseña debe tener mínimo 6 caracteres.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,'.$usuario->id,
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:admin,propietario,cliente',
            'activo' => 'required|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'rol' => $validated['rol'],
            'activo' => $validated['activo'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado satisfactoriamente.');
    }

    public function toggleActivo(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta de administrador.');
        }

        $usuario->activo = ! $usuario->activo;
        $usuario->save();

        $estado = $usuario->activo ? 'activada' : 'desactivada';

        return back()->with('success', "La cuenta de {$usuario->name} ha sido {$estado}.");
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta de administrador.');
        }

        if ($usuario->isAdmin()) {
            $totalAdmins = User::where('rol', 'admin')->count();
            if ($totalAdmins <= 1) {
                return back()->with('error', 'No se puede eliminar el único administrador del sistema.');
            }
        }

        if ($usuario->hasActiveReservations()) {
            $msg = $usuario->isPropietario()
                ? "No se puede eliminar a {$usuario->name} porque su local tiene canchas con reservas confirmadas activas. Deben cancelarse o completarse primero."
                : "No se puede eliminar a {$usuario->name} porque tiene reservas confirmadas activas pendientes.";

            return back()->with('error', $msg);
        }

        $nombre = $usuario->name;
        $usuario->deleteWithRelations();

        return redirect()->route('admin.usuarios.index')
            ->with('success', "El usuario {$nombre} y sus datos asociados han sido eliminados correctamente.");
    }
}
