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

        // Validar permiso solo si es funcionario (fundación tiene acceso total)
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('crear mascotas')) {
                return response()->json(['message' => 'No tienes permiso para crear mascotas'], 403);
            }
        }

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('mascotas', 'public');
            $validated['foto'] = $fotoPath;
        }

        $mascota = Mascota::create($validated + ['estado' => 'habilitado']);

        return response()->json([
            'message' => 'Mascota registrada correctamente',
            'data' => $mascota
        ], 201);
    }

    // 👉 Actualizar una mascota
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

        // Validar permiso solo si es funcionario (fundación tiene acceso total)
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('Editar mascotas')) {
                return response()->json(['message' => 'No tienes permiso para editar mascotas'], 403);
            }
        }

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

    // 👉 Deshabilitar (no eliminar)
    public function eliminar(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:mascotas,id',
        ]);

        $mascota = Mascota::findOrFail($validated['id']);
        
        // Validar permiso solo si es funcionario (fundación tiene acceso total)
        $user = $request->user();
        if ($user instanceof \App\Models\Funcionario) {
            if (!$user->can('eliminar mascotas')) {
                return response()->json(['message' => 'No tienes permiso para eliminar mascotas'], 403);
            }
        }
        
        $mascota->estado = 'deshabilitado';
        $mascota->save();

        return response()->json([
            'message' => 'Mascota deshabilitada correctamente',
            'mascota' => $mascota
        ], 200);
    }

    // 👉 Mostrar todas (opcional)
    public function listarTodas()
    {
        $mascotas = Mascota::all();
        return response()->json($mascotas, 200);
    }

    // 👉 NUEVO: Obtener 10 mascotas habilitadas aleatorias
    public function aleatorias()
    {
        $mascotas = Mascota::where('estado', 'habilitado')
            ->inRandomOrder()
            ->take(10)
            ->get();

        $mascotas->transform(function ($mascota) {
            if ($mascota->foto) {
                $mascota->foto = asset('storage/' . $mascota->foto);
            }
            return $mascota;
        });

        return response()->json($mascotas, 200);
    }
}
