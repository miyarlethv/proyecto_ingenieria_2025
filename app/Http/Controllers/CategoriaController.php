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
        $categoria = Categoria::create(['categoria' => $request->categoria]);
        return response()->json($categoria, 201);
    }

    public function crearNombre(Request $request)
    {
        $nombre = Nombre::create(['nombre' => $request->nombre]);
        return response()->json($nombre, 201);
    }

    public function actualizarCategoria(Request $request)
    {
        $categoria = Categoria::find($request->id);
        $categoria->update(['categoria' => $request->categoria]);
        return response()->json($categoria);
    }

    public function actualizarNombre(Request $request)
    {
        $nombre = Nombre::find($request->id);
        $nombre->update(['nombre' => $request->nombre]);
        return response()->json($nombre);
    }

    public function eliminarCategoria(Request $request)
    {
        Categoria::destroy($request->id);
        return response()->json(['message' => 'Eliminado']);
    }

    public function eliminarNombre(Request $request)
    {
        Nombre::destroy($request->id);
        return response()->json(['message' => 'Eliminado']);
    }
}