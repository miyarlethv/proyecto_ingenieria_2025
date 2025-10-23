<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mascota;

class MascotaController extends Controller
{
    // 👉 Listar solo las mascotas habilitadas
    public function index()
    {
        $mascotas = Mascota::where('estado', 'habilitado')->get();
        return response()->json($mascotas, 200);
    }

    // 👉 Registrar una mascota
    public function crear(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|string|max:50',
            'caracteristicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mascotas', 'public');
            $validated['foto'] = $fotoPath;
        }

        // Se crea habilitada por defecto
        $mascota = Mascota::create($validated + ['estado' => 'habilitado']);

        return response()->json([
            'message' => 'Mascota registrada correctamente',
            'data' => $mascota
        ], 201);
    }

    // Actualizar una mascota
    public function actualizar(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:mascotas,id',
            'nombre' => 'required|string|max:255',
            'edad' => 'required|string|max:50',
            'caracteristicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $mascota = Mascota::findOrFail($validated['id']);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mascotas', 'public');
            $validated['foto'] = $fotoPath;
        }

        $mascota->update($validated);

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'data' => $mascota
        ], 200);
    }

    // 👉 Deshabilitar (no eliminar) una mascota
    public function eliminar(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:mascotas,id',
        ]);

        $mascota = Mascota::findOrFail($validated['id']);
        $mascota->estado = 'deshabilitado';
        $mascota->save();

        return response()->json([
            'message' => 'Mascota deshabilitada correctamente',
            'mascota' => $mascota
        ], 200);
    }

    // 👉 (Opcional) Mostrar todas (habilitadas y deshabilitadas)
    public function listarTodas()
    {
        $mascotas = Mascota::all();
        return response()->json($mascotas, 200);
    }
}
