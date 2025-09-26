<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mascota;

class MascotaController extends Controller
{
    // 👉 Listar todas las mascotas
    public function index()
    {
        $mascotas = Mascota::listarTodas();
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

        $mascota = Mascota::registrarMascota($validated);
        return response()->json([
            'message' => 'Mascota registrada correctamente',
            'data' => $mascota
        ], 201);
    }

    // 👉 Actualizar una mascota
    public function actualizar(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'nombre' => 'required|string|max:255',
            'edad' => 'required|string|max:50',
            'caracteristicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mascotas', 'public');
            $validated['foto'] = $fotoPath;
        }

        $mascota = Mascota::ActualizarMascota($validated);
        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'data' => $mascota
        ], 200);
    }

    // 👉 Eliminar una mascota
    public function eliminar(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
        ]);
        Mascota::eliminarMascota($validated['id']);
        return response()->json([
            'message' => 'Mascota eliminada correctamente'
        ], 200);
    }
}
