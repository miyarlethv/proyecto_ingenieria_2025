<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionarios';

    protected $fillable = [
        'nombre',
        'tipo_documento',
        'nit',
        'telefono',
        'email',
        'rol_id',
        'password',
       
    ];

    // 🔒 Encriptar contraseña automáticamente al guardar
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($funcionario) {
            if ($funcionario->isDirty('password')) {
                $funcionario->password = Hash::make($funcionario->password);
            }
        });
    }

    // 🔗 Relación con la tabla roles
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
