<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Fundacion;
use Illuminate\Http\Request;

class FundacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Listar todas las fundaciones activas
    public function index()
    {
        $fundaciones = Fundacion::all();
        return response()->json(data: $fundaciones, status: 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    // Crear nueva fundacion
   public function crear(Request $request)
{
    $request->validate([
        'nombre' => 'required',
        'nit' => 'required|unique:fundaciones',
        'email' => 'required|email|unique:fundaciones',
        'telefono' => 'required',
        'direccion' => 'required',
        'password' => 'required|min:8',
        'slogan' => 'required',
        'logo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    if ($request->hasFile('logo')) {
        $path = $request->file('logo')->store('logos', 'public');
        $request->merge(['logo' => $path]);
    }

    $fundacion = Fundacion::crear($request->all());

    // 🔗 Agrega la URL pública
    $fundacion->logo_url = asset('storage/' . $fundacion->logo);

    return response()->json($fundacion, 201);
}

    /**
     * Display the specified resource.
     */
    // Mostrar fundacion por ID
    public function traerPersonaId(string $id)
    {
        $fundacion = Fundacion::findOrFail($id);
        return response()->json(data: $fundacion, status: 200);
    }


     // Actualizar fundacion
    public function actualizar(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'nit' => 'unique:fundaciones',
            'email' => 'email|unique:fundaciones',
            'password' => 'min:8',
            'slogan' => 'required',
            'logo' => 'required',

        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $request->merge(['logo' => $path]);
        }


        $fundacion = Fundacion::actualizar($request->all());

        return response()->json($fundacion, 200);
    }

   // Eliminar fundacion (soft delete)
    public function eliminarPorId(Request $request)
    {
        Fundacion::eliminarPorId($request->all());
        return response()->json(['message' => 'Fundacion eliminada'], 200);
    }
}
