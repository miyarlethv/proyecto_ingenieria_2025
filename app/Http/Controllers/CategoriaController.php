<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Nombre;

class CategoriaController extends Controller
{
   public function index()
    {
        return response()->json(Categoria::all());
    }

    public function getNombres()
    {
        return response()->json(Nombre::all());
    }

    public function crearCategoria(Request $request)
    {
        // Validar permiso solo si es funcionario
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Gestionar Categorías')) {
                return response()->json(['message' => 'No tienes permiso para crear categorías'], 403);
            }
        }

        $categoria = Categoria::create(['categoria' => $request->categoria]);
        return response()->json($categoria, 201);
    }

    public function crearNombre(Request $request)
    {
        // Validar permiso solo si es funcionario
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Gestionar Categorías')) {
                return response()->json(['message' => 'No tienes permiso para crear nombres'], 403);
            }
        }

        $nombre = Nombre::create(['nombre' => $request->nombre]);
        return response()->json($nombre, 201);
    }

    public function actualizarCategoria(Request $request)
    {
        // Validar permiso solo si es funcionario
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Gestionar Categorías')) {
                return response()->json(['message' => 'No tienes permiso para editar categorías'], 403);
            }
        }

        $categoria = Categoria::find($request->id);
        $categoria->update(['categoria' => $request->categoria]);
        return response()->json($categoria);
    }

    public function actualizarNombre(Request $request)
    {
        // Validar permiso solo si es funcionario
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Gestionar Categorías')) {
                return response()->json(['message' => 'No tienes permiso para editar nombres'], 403);
            }
        }

        $nombre = Nombre::find($request->id);
        $nombre->update(['nombre' => $request->nombre]);
        return response()->json($nombre);
    }

    public function eliminarCategoria(Request $request)
    {
        // Validar permiso solo si es funcionario
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Gestionar Categorías')) {
                return response()->json(['message' => 'No tienes permiso para eliminar categorías'], 403);
            }
        }

        Categoria::destroy($request->id);
        return response()->json(['message' => 'Eliminado']);
    }

    public function eliminarNombre(Request $request)
    {
        // Validar permiso solo si es funcionario
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Gestionar Categorías')) {
                return response()->json(['message' => 'No tienes permiso para eliminar nombres'], 403);
            }
        }

        Nombre::destroy($request->id);
        return response()->json(['message' => 'Eliminado']);
    }
}