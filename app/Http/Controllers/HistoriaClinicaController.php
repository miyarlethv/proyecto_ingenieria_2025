<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriaClinica;

class HistoriaClinicaController extends Controller
{
    // Crear una nueva historia clínica
    public function crear(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'fecha' => 'required|date',
            'descripcion' => 'required|string',
            'tipo' => 'nullable|string',
        ]);

        $historia = HistoriaClinica::create([
            'mascota_id' => $request->mascota_id,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
        ]);

        return response()->json([
            'message' => 'Historia clínica creada correctamente',
            'data' => $historia
        ], 201);
    }

    // Mostrar todas las historias o solo las de una mascota (si se pasa el id)
    public function index(Request $request)
    {
        if ($request->has('mascota_id')) {
            $historias = HistoriaClinica::where('mascota_id', $request->mascota_id)->get();
        } else {
            $historias = HistoriaClinica::all();
        }

        return response()->json([
            'data' => $historias
        ], 200);
    }
}
