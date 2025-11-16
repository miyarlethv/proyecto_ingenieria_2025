<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nombre;

class ProductoNombreController extends Controller
{
    // Listar todos los nombres activos
    public function index()
    {
        $nombres = Nombre::with('categoria')
            ->where('activo', true)
            ->get()
            ->map(function ($nombre) {
                return [
                    'id' => $nombre->id,
                    'nombre' => $nombre->nombre,
                    'categoria_id' => $nombre->categoria_id,
                    'categoria' => $nombre->categoria ? $nombre->categoria->categoria : null,
                    'activo' => $nombre->activo,
                    'created_at' => $nombre->created_at,
                    'updated_at' => $nombre->updated_at,
                ];
            });
        return response()->json($nombres, 200);
    }

    // Crear nuevo nombre
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:producto_nombres',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $nombre = Nombre::create([
            'nombre' => $request->nombre,
            'categoria_id' => $request->categoria_id,
            'activo' => true
        ]);

        $nombre->load('categoria');

        return response()->json(['message' => 'Nombre creado correctamente', 'data' => $nombre], 201);
    }

    // Actualizar nombre
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:producto_nombres,id',
            'nombre' => 'required|string|max:100|unique:producto_nombres,nombre,' . $request->id,
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $nombre = Nombre::findOrFail($request->id);
        $nombre->nombre = $request->nombre;
        $nombre->categoria_id = $request->categoria_id;
        $nombre->save();

        $nombre->load('categoria');

        return response()->json(['message' => 'Nombre actualizado correctamente', 'data' => $nombre], 200);
    }

    // Eliminar nombre (soft delete)
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:producto_nombres,id',
        ]);

        $nombre = Nombre::findOrFail($request->id);
        $nombre->activo = false;
        $nombre->save();

        return response()->json(['message' => 'Nombre eliminado correctamente'], 200);
    }
}
