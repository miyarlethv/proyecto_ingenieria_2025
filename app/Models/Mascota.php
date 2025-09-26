<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $fillable = [
        'nombre',
        'edad',
        'caracteristicas',
        'foto',
    ];

    // Listar todas las mascotas
    public static function listarTodas()
    {
        return self::all();
    }

    // Registrar una mascota
    public static function registrarMascota($data)
    {
        return self::create([
            'nombre' => $data['nombre'],
            'edad' => $data['edad'],
            'caracteristicas' => $data['caracteristicas'] ?? null,
            'foto' => $data['foto'] ?? null,
        ]);
    }

    // Actualizar una mascota
    public static function actualizarMascota($data)
    {
        $mascota = self::findOrFail($data['id']);
        $mascota->nombre = $data['nombre'];
        $mascota->edad = $data['edad'];
        $mascota->caracteristicas = $data['caracteristicas'] ?? $mascota->caracteristicas;
        if (isset($data['foto'])) {
            $mascota->foto = $data['foto'];
        }
        $mascota->save();
        return $mascota;
    }

    // Eliminar una mascota
    public static function eliminarMascota($id)
    {
        $mascota = self::findOrFail($id);
        $mascota->delete();
    }
}


