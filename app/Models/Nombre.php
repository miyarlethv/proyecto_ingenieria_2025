<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nombre extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'nombre_id');
    }
}
