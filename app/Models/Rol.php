<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Rol extends SpatieRole
{
    use HasFactory;

    // Nombre real de la tabla
    protected $table = 'roles';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',         // Spatie usa "name" internamente
        'descripcion',  // campo adicional personalizado
        'guard_name',   // necesario para Spatie
    ];

    /**
     * Relación con permisos (permiso_rol)
     */
    public function permisos()
    {
        // Usa el modelo personalizado Permiso (que extiende de Spatie\Permission\Models\Permission)
        return $this->belongsToMany(
            Permiso::class,
            'role_has_permissions', // nombre real de la tabla pivote de Spatie
            'role_id',
            'permission_id'
        );
    }
}
