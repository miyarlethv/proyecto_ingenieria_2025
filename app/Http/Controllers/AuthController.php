<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Fundacion;

class AuthController extends Controller
{
    /**
     * Obtener información del usuario autenticado (Fundación o Funcionario)
     */
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'No autenticado'
            ], 401);
        }

        // Verificar si es Fundación
        if ($user instanceof Fundacion) {
            return response()->json([
                'tipo' => 'fundacion',
                'nombre' => $user->nombre,
                'email' => $user->email,
                'permisos' => 'all', // La fundación tiene todos los permisos
                'data' => $user
            ], 200);
        }

        // Verificar si es Funcionario
        if ($user instanceof Funcionario) {
            // Obtener permisos con sus URLs
            $permisos = $user->getAllPermissions()->map(function ($permiso) {
                return [
                    'id' => $permiso->id,
                    'name' => $permiso->name,
                    'url' => $permiso->url ?? null,
                    'descripcion' => $permiso->descripcion ?? null,
                ];
            });

            return response()->json([
                'tipo' => 'funcionario',
                'nombre' => $user->nombre,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permisos' => $permisos,
                'data' => $user
            ], 200);
        }

        return response()->json([
            'message' => 'Tipo de usuario no reconocido'
        ], 400);
    }

    /**
     * Cerrar sesión (revocar token actual)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ], 200);
    }
}
