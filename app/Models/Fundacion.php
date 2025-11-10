<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Fundacion extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'fundaciones';

    protected $fillable = [
        'nombre',
        'nit',
        'email',
        'telefono',
        'direccion',
        'password',
        'slogan',
        'logo'
    ];

    protected $hidden = ['password'];

    // Crear nueva fundacion con password encriptado
    public static function crear(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        return self::create($data);
    }

    // Actualizar fundacion
    public function actualizar(array $data): bool
    {
        $fundacion = self::findOrFail($data['id']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $fundacion->update($data);
    }

    public static function eliminarPorId($id): bool
    {
        $fundacion = self::findOrFail($id);
        return $fundacion->delete();
    }

}
