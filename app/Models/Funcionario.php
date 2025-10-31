<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Importante para login
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens; // ✅ Para tokens API
use Spatie\Permission\Traits\HasRoles;

class Funcionario extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles;

    // Nombre de la tabla
    protected $table = 'funcionarios';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'nit',
        'telefono',
        'email',
        'password',
    ];

    // Guard que usará Spatie (por defecto 'web')
    protected $guard_name = 'web';

    /**
     * 🔒 Encripta automáticamente la contraseña antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($funcionario) {
            if ($funcionario->isDirty('password')) {
                $funcionario->password = Hash::make($funcionario->password);
            }
        });
    }

    /**
     * 🔒 Oculta campos sensibles en las respuestas JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
