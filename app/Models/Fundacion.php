<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Fundacion extends Model
{
    use SoftDeletes, HasApiTokens;

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

    // Crear nueva persona
    public static function crear(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        return self::create($data);
    }

    // Actualizar fundacion
    public function actualizar(array $data): bool
    {
        $persona = self::findOrFail($data['id']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->update($data);
    }

    public static function eliminarPorId($id): bool
    {
        $persona = self::findOrFail($id);
        return $persona->delete();
    }

    

}
