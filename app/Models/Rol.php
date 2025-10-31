<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Rol extends SpatieRole
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'descripcion',
        'guard_name',
    ];

    /**
     * Relación personalizada con permisos (en español)
     */
    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(
            Permiso::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Alias en inglés para mantener compatibilidad con Spatie
     */
    public function permissions(): BelongsToMany
    {
        return $this->permisos();
    }
}
