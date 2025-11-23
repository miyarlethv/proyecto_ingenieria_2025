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
            // Crear token de API para la fundación
            $token = $fundacion->createToken('fundacion_token')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'fundacion',
                'nombre' => $fundacion->nombre,
                'fundacion_id' => $fundacion->id, // ✅ AGREGADO
                'token' => $token,
                'data' => $fundacion
            ], 200);
        }

        // 🔹 3️⃣ Verificar si es un Funcionario
        $funcionario = Funcionario::where('email', $request->email)->first();
        if ($funcionario && Hash::check($request->password, $funcionario->password)) {

            // Crear token de API para el funcionario
            $token = $funcionario->createToken('funcionario_token')->plainTextToken;

            // Obtener permisos con sus URLs
            $permisos = $funcionario->getAllPermissions()->map(function ($permiso) {
                return [
                    'id' => $permiso->id,
                    'name' => $permiso->name,
                    'url' => $permiso->url ?? null,
                    'descripcion' => $permiso->descripcion ?? null,
                ];
            });

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'tipo' => 'funcionario',
                'nombre' => $funcionario->nombre,
                'email' => $funcionario->email,
                'fundacion_id' => $funcionario->fundacion_id ?? null, // ✅ AGREGADO (si el funcionario pertenece a una fundación)
                'roles' => $funcionario->getRoleNames(),
                'permisos' => $permisos,
                'token' => $token,
                'data' => $funcionario
            ], 200);
        }

        // 🔹 Si no se encontró en ninguna tabla
        return response()->json([
            'message' => 'Credenciales incorrectas. Por favor, verifica tu correo y contraseña.'
        ], 401);
    }
}