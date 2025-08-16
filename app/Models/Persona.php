<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';

    protected $fillable = ['nit', 'nombre', 'email', 'telefono', 'direccion', 'password'];
    protected $hidden = ['password'];

    // Crear nueva persona
    public static function crear(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        return self::create($data);
    }

    // Actualizar persona
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
