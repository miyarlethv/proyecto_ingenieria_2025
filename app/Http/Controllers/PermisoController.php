<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    /**
     * Mostrar todos los permisos
     */
    public function index()
    {
        $permisos = Permission::all();
        return response()->json($permisos, 200);
    }

    /**
     * Crear un nuevo permiso
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'descripcion' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        // 👇 Agregamos automáticamente el guard_name
        $data['guard_name'] = 'web';

        $permiso = Permission::create($data);

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
            'id' => 'required|integer|exists:permissions,id',
            'name' => 'required|string|unique:permissions,name,' . $request->id,
            'descripcion' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        // 👇 También nos aseguramos de incluirlo en actualización
        $data['guard_name'] = 'web';

        $permiso = Permission::findOrFail($data['id']);
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
            'id' => 'required|integer|exists:permissions,id',
        ]);

        $permiso = Permission::findOrFail($data['id']);
        $permiso->delete();

        return response()->json([
            'message' => 'Permiso eliminado correctamente',
        ], 200);
    }
}
