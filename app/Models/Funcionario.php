<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Funcionario extends Model
{
    use HasFactory, HasRoles;

    // Nombre de la tabla
    protected $table = 'funcionarios';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'nit',
        'telefono',
        'email',
        'rol_id',
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
     * 🔗 Relación con la tabla roles (si aún la usas en tu sistema)
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
