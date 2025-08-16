<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Listar todas las personas activas
    public function index()
    {
        $personas = Persona::all();
        return response()->json(data: $personas, status: 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // Crear nueva persona
    public function crear(Request $request)
    {
        $request->validate([
            'nit' => 'required|unique:personas',
            'nombre' => 'required',
            'email' => 'required|email|unique:personas',
            'telefono' => 'required',
            'direccion' => 'required',
            'password' => 'required|min:8',
        ]);
        

        $persona = Persona::crear($request->all());
        return response()->json( data: $persona, status: 200);
    }

    /**
     * Display the specified resource.
     */
    // Mostrar persona por ID
    public function traerPersonaId(string $id)
    {
        $persona = Persona::findOrFail($id);
        return response()->json(data: $persona, status: 200);
    }


     // Actualizar persona
    public function actualizar(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'nit' => 'unique:personas',
            'email' => 'email|unique:personas',
            'password' => 'min:8',
        ]);

        $persona = Persona::actualizar($request->all());

        return response()->json($persona, 200);
    }

   // Eliminar persona (soft delete)
    public function eliminarPorId(Request $request)
    {
        Persona::eliminarPorId($request->all());
        return response()->json(['message' => 'Persona eliminada'], 200);
    }
}
