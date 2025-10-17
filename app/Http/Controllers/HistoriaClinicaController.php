<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriaClinica;

class HistoriaClinicaController extends Controller
{
    /**
     * Crear una nueva historia clínica
     */
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
            'data' => $historia,
        ], 201);
    }

    /**
     * Listar historias (todas o por mascota)
     */
    public function index(Request $request)
    {
        $historias = $request->has('mascota_id')
            ? HistoriaClinica::where('mascota_id', $request->mascota_id)->get()
            : HistoriaClinica::all();

        return response()->json([
            'data' => $historias,
        ], 200);
    }

    /**
     * Actualizar una historia clínica (recibe id en el body)
     */
    public function actualizar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:historias_clinicas,id',
                'fecha' => 'required|date',
                'descripcion' => 'required|string',
                'tipo' => 'nullable|string',
            ]);

            $historia = HistoriaClinica::findOrFail($request->id);

            $historia->update([
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
            ]);

            return response()->json([
                'message' => 'Historia clínica actualizada correctamente',
                'data' => $historia,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar la historia clínica',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar una historia clínica (recibe id en el body)
     */
    public function eliminar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:historias_clinicas,id',
            ]);

            $historia = HistoriaClinica::findOrFail($request->id);
            $historia->delete();

            return response()->json([
                'message' => 'Historia clínica eliminada correctamente',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la historia clínica',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }
}
