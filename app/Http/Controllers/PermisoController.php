<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permiso;

class PermisoController extends Controller
{
    /**
     * Mostrar todos los permisos
     */
    public function index()
    {
        $permisos = Permiso::all();
        return response()->json($permisos, 200);
    }

    /**
     * Crear un nuevo permiso
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|unique:permisos,nombre',
            'descripcion' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        $permiso = Permiso::create($data);

        return response()->json([
            'message' => 'Permiso creado correctamente',
            'data' => $permiso,
        ], 201);
    }

    /**
     * Actualizar un permiso existente
     */
    public function actualizar(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:permisos,id',
            'nombre' => 'required|string|unique:permisos,nombre,' . $request->id,
            'descripcion' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        $permiso = Permiso::findOrFail($data['id']);
        $permiso->update($data);

        return response()->json([
            'message' => 'Permiso actualizado correctamente',
            'data' => $permiso,
        ], 200);
    }

    /**
     * Eliminar un permiso
     */
    public function eliminar(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:permisos,id',
        ]);

        $permiso = Permiso::findOrFail($data['id']);
        $permiso->delete();

        return response()->json([
            'message' => 'Permiso eliminado correctamente',
        ], 200);
    }
}
