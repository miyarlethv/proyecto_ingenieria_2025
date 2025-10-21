<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // Listar todos los roles con sus permisos
    public function index()
    {
        $roles = Rol::with('permisos')->get();
        return response()->json($roles);
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|unique:roles,nombre',
            'descripcion' => 'nullable|string',
            'permisos' => 'array'
        ]);

        $rol = Rol::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null
        ]);

        if (!empty($data['permisos'])) {
            $rol->permisos()->sync($data['permisos']);
        }

        return response()->json([
            'message' => 'Rol creado correctamente',
            'data' => $rol->load('permisos')
        ], 201);
    }

    // Actualizar un rol existente
    public function actualizar(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:roles,id',
            'nombre' => 'required|string|unique:roles,nombre,' . $request->id,
            'descripcion' => 'nullable|string',
            'permisos' => 'array'
        ]);

        $rol = Rol::findOrFail($data['id']);
        $rol->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null
        ]);

        if (isset($data['permisos'])) {
            $rol->permisos()->sync($data['permisos']);
        }

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'data' => $rol->load('permisos')
        ]);
    }

    // Eliminar un rol
    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:roles,id'
        ]);

        $rol = Rol::findOrFail($request->id);
        $rol->delete();

        return response()->json(['message' => 'Rol eliminado correctamente']);
    }
}
