<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mascota;

class MascotaController extends Controller
{
    // 👉 Listar todas las mascotas
    public function index()
    {
        return response()->json(Mascota::all(), 200);
    }

    // 👉 Registrar una mascota
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|string|max:50',
            'caracteristicas' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Guardar foto si existe
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mascotas', 'public');
        }

        // Crear mascota
        $mascota = Mascota::create([
            'nombre' => $validated['nombre'],
            'edad' => $validated['edad'],
            'caracteristicas' => $validated['caracteristicas'] ?? null,
            'foto' => $fotoPath,
        ]);

        return response()->json([
            'message' => 'Mascota registrada correctamente',
            'data' => $mascota
        ], 201);
    }
}
