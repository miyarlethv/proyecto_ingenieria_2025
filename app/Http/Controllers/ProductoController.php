<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    // === Listar productos ===
    public function index()
    {
        $productos = Producto::with(['categoriaRelacion', 'nombreRelacion'])
            ->where('activo', true)
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->id,
                    'categoria_id' => $producto->categoria_id,
                    'nombre_id' => $producto->nombre_id,
                    'categoria' => $producto->categoriaRelacion ? $producto->categoriaRelacion->categoria : $producto->categoria,
                    'nombre' => $producto->nombreRelacion ? $producto->nombreRelacion->nombre : $producto->nombre,
                    'cantidad' => $producto->cantidad,
                    'foto' => $producto->foto,
                    'activo' => $producto->activo,
                    'created_at' => $producto->created_at,
                    'updated_at' => $producto->updated_at,
                ];
            });
        return response()->json($productos);
    }

    // === Crear producto ===
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'nombre_id' => 'nullable|exists:nombres,id',
            'cantidad' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto = new Producto();
        $producto->categoria_id = $request->categoria_id;
        $producto->nombre_id = $request->nombre_id;
        $producto->cantidad = $request->cantidad;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('productos', 'public');
            $producto->foto = $path;
        }

        $producto->save();
        $producto->load(['categoriaRelacion', 'nombreRelacion']);

        return response()->json(['message' => 'Producto creado correctamente', 'data' => $producto]);
    }

    // === Actualizar producto ===
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:productos,id',
            'categoria_id' => 'nullable|exists:categorias,id',
            'nombre_id' => 'nullable|exists:nombres,id',
            'cantidad' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $producto = Producto::findOrFail($request->id);
        $producto->categoria_id = $request->categoria_id;
        $producto->nombre_id = $request->nombre_id;
        $producto->cantidad = $request->cantidad;

        if ($request->hasFile('foto')) {
            if ($producto->foto && Storage::disk('public')->exists($producto->foto)) {
                Storage::disk('public')->delete($producto->foto);
            }
            $path = $request->file('foto')->store('productos', 'public');
            $producto->foto = $path;
        }

        $producto->save();
        $producto->load(['categoriaRelacion', 'nombreRelacion']);

        return response()->json(['message' => 'Producto actualizado correctamente', 'data' => $producto]);
    }

    // === Eliminar producto (deshabilitar) ===
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:productos,id',
        ]);

        $producto = Producto::find($request->id);
        $producto->activo = false;
        $producto->save();

        return response()->json(['message' => 'Producto deshabilitado correctamente']);
    }
}
