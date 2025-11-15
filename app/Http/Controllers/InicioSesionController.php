<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Fundacion;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        // 🔹 1️⃣ Verificar si es una Persona
        $persona = Persona::where('email', $request->email)->first();
        if ($persona && Hash::check($request->password, $persona->password)) {
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'persona',
                'nombre' => $persona->nombre,
                'token' => $persona->createToken('auth_token')->plainTextToken,
                'data' => $persona
            ], 200);
        }

        // 🔹 2️⃣ Verificar si es una Fundación
        $fundacion = Fundacion::where('email', $request->email)->first();
        if ($fundacion && Hash::check($request->password, $fundacion->password)) {
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'fundacion',
                'nombre' => $fundacion->nombre,
                'token' => $fundacion->createToken('auth_token')->plainTextToken,
                'data' => $fundacion
            ], 200);
        }

        // 🔹 3️⃣ Verificar si es un Funcionario
        $funcionario = Funcionario::where('email', $request->email)->first();
        if ($funcionario && Hash::check($request->password, $funcionario->password)) {

            // Iniciar sesión con Auth para usar Sanctum
            Auth::login($funcionario);

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'funcionario',
                'nombre' => $funcionario->nombre,
                'roles' => $funcionario->getRoleNames(),
                'permissions' => $funcionario->getAllPermissions()->pluck('name'),
                'token' => $funcionario->createToken('auth_token')->plainTextToken,
                'data' => $funcionario
            ], 200);
        }

        // 🔹 Si no se encontró en ninguna tabla
        return response()->json([
            'message' => 'Credenciales incorrectas. Por favor, verifica tu correo y contraseña.'
        ], 401);
    }
}
