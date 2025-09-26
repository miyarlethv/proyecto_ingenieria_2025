<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Fundacion;
use Illuminate\Support\Facades\Hash;

class InicioSesionController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo :attribute debe ser un correo válido.'
        ]);

        // Buscar en tabla Persona
        $persona = Persona::where('email', $request->email)->first();

        if ($persona && Hash::check($request->password, $persona->password)) {
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'usuario',
                'nombre' => $persona->nombre,
                'data' => $persona
            ], 200);
        }

        // Si no está en Persona, buscar en Fundacion
        $fundacion = Fundacion::where('email', $request->email)->first();

        if ($fundacion && Hash::check($request->password, $fundacion->password)) {
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'fundacion',
                'nombre' => $fundacion->nombre,
                'data' => $fundacion
            ], 200);
        }

    return response()->json(['message' => 'Credenciales incorrectas. Por favor, verifica tu correo y contraseña.'], 401);
    }
}
