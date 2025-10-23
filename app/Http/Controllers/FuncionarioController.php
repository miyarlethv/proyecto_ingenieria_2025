<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class FuncionarioController extends Controller
{
    // ==============================
    // 🔹 Listar todos los funcionario
    // ==============================
    public function index()
    {
        $funcionario = Funcionario::with('rol')->get();
        return response()->json($funcionario);
    }

    // ==============================
    // 🔹 Crear un nuevo funcionario
    // ==============================
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'tipo_documento' => 'nullable|string|max:10',
            'nit' => 'required|string|max:50|unique:funcionarios,nit',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150|unique:funcionarios,email',
            'rol_id' => 'required|integer|exists:roles,id',
            'password' => 'required|string|min:6',
            
        ]);

        $data['password'] = Hash::make($data['password']);

        $funcionario = Funcionario::create($data);

        return response()->json([
            'message' => 'Funcionario creado correctamente',
            'data' => $funcionario->load('rol')
        ], 201);
    }

    // ==============================
    // 🔹 Actualizar funcionario (sin ID en URL)
    // ==============================
    public function actualizar(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:funcionarios,id',
            'nombre' => 'required|string|max:150',
            'tipo_documento' => 'required|string|max:10',
            'nit' => 'required|string|max:50|unique:funcionarios' . $request->id,
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique;funcionarios' . $request->id,
            'rol_id' => 'required|integer|exists:roles,id',
            'password' => 'required|string|min:6',
            
        ]);

        $funcionario = Funcionario::findOrFail($data['id']);

        // Si llega una nueva contraseña, la encripta
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $funcionario->update($data);

        return response()->json([
            'message' => 'Funcionario actualizado correctamente',
            'data' => $funcionario->load('rol')
        ]);
    }

    // ==============================
    // 🔹 Eliminar funcionario (sin ID en URL)
    // ==============================
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
