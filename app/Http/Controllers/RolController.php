<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolController extends Controller
{
    /**
     * Muestra todos los roles con sus permisos.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    /**
     * Crea un nuevo rol con su descripción y permisos asociados.
     */
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'descripcion' => 'nullable|string',
    ]);

    // Crear el rol con nombre, descripción y guard_name
    $role = Role::create([
        'name' => $request->name,
        'descripcion' => $request->descripcion,
        'guard_name' => 'web', // 🔹 Spatie necesita este campo
    ]);

    // Asignar permisos (si existen en la solicitud)
    if ($request->has('permissions')) {
        $role->syncPermissions($request->permissions);
    }

    return response()->json([
        'message' => 'Rol creado correctamente',
        'data' => $role->load('permissions'),
    ], 201);
}


    /**
     * Muestra la información de un rol específico junto con sus permisos.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    /**
     * Actualiza el nombre, descripción y permisos de un rol existente.
     */
    public function actualizar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:roles,id',
            'name' => 'required|unique:roles,name,' . $request->id,
            'descripcion' => 'nullable|string',
        ]);

        $role = Role::findOrFail($request->id);

        // Actualizar los campos del rol
        $role->update([
            'name' => $request->name,
            'descripcion' => $request->descripcion,
        ]);

        // Actualizar los permisos si fueron enviados
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'message' => 'Rol actualizado correctamente',
            'data' => $role->load('permissions'),
        ]);
    }


    /**
     * Elimina un rol por su ID.
     */
    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->id);
        $role->delete();

        return response()->json(['message' => 'Rol eliminado correctamente']);
    }


    /**
     * Devuelve todos los permisos disponibles.
     */
    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }
}
