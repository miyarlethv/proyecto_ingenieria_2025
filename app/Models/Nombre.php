<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nombre extends Model
{
    use SoftDeletes;

    protected $table = 'producto_nombres';

    protected $fillable = [
        'nombre',
        'categoria_id',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'nombre_id');
    }
}
