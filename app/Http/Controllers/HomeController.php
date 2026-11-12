<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Establecimiento;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Establecimiento::where('activo', 1)->with(['canchas' => function ($q) {
            $q->where('activa', 1);
        }]);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('nombre', 'like', "%{$q}%")
                    ->orWhere('direccion', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
            });
        }

        $establecimientos = $query->paginate(9);

        $totalCanchas = Cancha::where('activa', 1)->count();
        $totalEstablecimientos = Establecimiento::where('activo', 1)->count();

        return view('home', compact('establecimientos', 'totalCanchas', 'totalEstablecimientos'));
    }
}
