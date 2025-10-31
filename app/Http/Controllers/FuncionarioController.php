<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Rol; // Tu modelo de roles, usado como referencia
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    // Listar todos los funcionarios con su rol de Spatie
    public function index()
    {
       $funcionarios = Funcionario::with('roles')->get(); // Spatie roles
        return response()->json($funcionarios);
    }
    

    // Crear nuevo funcionario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => ['required', Rule::in(['CC', 'TI', 'CE'])],
            'nit' => 'required|string|max:50|unique:funcionarios,nit',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:funcionarios,email',
            'password' => 'required|string|min:6',
        ]);

        $funcionario = Funcionario::create([
            'nombre' => $request->nombre,
            'tipo_documento' => $request->tipo_documento,
            'nit' => $request->nit,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => $request->password, // Se encripta en el modelo
        ]);
        
        // Asignar rol en Spatie
        $funcionario->assignRole($request->rol);

        return response()->json([
            'message' => 'Funcionario creado correctamente',
            'data' => $funcionario->load('roles'),
        ], 201);

        
    }
    // Asignar rol usando Spatie

    // Actualizar funcionario
    public function actualizar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
            'nombre' => 'required|string|max:255',
            'tipo_documento' => ['required', Rule::in(['CC', 'TI', 'CE'])],
            'nit' => ['required', 'string', 'max:50', Rule::unique('funcionarios')->ignore($request->id)],
            'telefono' => 'nullable|string|max:20',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('funcionarios')->ignore($request->id)],
            'password' => 'nullable|string|min:6',
        ]);

        $funcionario = Funcionario::findOrFail($request->id);

        $funcionario->nombre = $request->nombre;
        $funcionario->tipo_documento = $request->tipo_documento;
        $funcionario->nit = $request->nit;
        $funcionario->telefono = $request->telefono;
        $funcionario->email = $request->email;
       

        if (!empty($request->password)) {
            $funcionario->password = $request->password;
        }

        $funcionario->save();

        // Actualizar rol en Spatie
        $funcionario->syncRoles($request->rol); // reemplaza roles

        return response()->json([
            'message' => 'Funcionario actualizado correctamente',
            'data' => $funcionario->load('roles'),
        ]);
    }

    // Eliminar funcionario
    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
        ]);

        $funcionario = Funcionario::findOrFail($request->id);
        $funcionario->delete();

        return response()->json(['message' => 'Funcionario eliminado correctamente']);
    }
}
