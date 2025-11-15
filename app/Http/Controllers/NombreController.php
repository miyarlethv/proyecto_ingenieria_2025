<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nombre;

class NombreController extends Controller
{
    // Listar todos los nombres activos
    public function index()
    {
        $nombres = Nombre::where('activo', true)->get();
        return response()->json($nombres, 200);
    }

    // Crear nuevo nombre
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:nombres',
        ]);

        $nombre = Nombre::create([
            'nombre' => $request->nombre,
            'activo' => true
        ]);

        return response()->json(['message' => 'Nombre creado correctamente', 'data' => $nombre], 201);
    }

    // Actualizar nombre
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:nombres,id',
            'nombre' => 'required|string|max:100|unique:nombres,nombre,' . $request->id,
        ]);

        $nombre = Nombre::findOrFail($request->id);
        $nombre->nombre = $request->nombre;
        $nombre->save();

        return response()->json(['message' => 'Nombre actualizado correctamente', 'data' => $nombre], 200);
    }

    // Eliminar nombre (soft delete)
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:nombres,id',
        ]);

        $nombre = Nombre::findOrFail($request->id);
        $nombre->activo = false;
        $nombre->save();

        return response()->json(['message' => 'Nombre eliminado correctamente'], 200);
    }
}
