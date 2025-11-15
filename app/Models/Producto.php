<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'categoria_id',
        'nombre_id',
        'nombre',
        'categoria',
        'cantidad',
        'foto',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'cantidad' => 'integer'
    ];

    // Relación con categoría
    public function categoriaRelacion()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con nombre
    public function nombreRelacion()
    {
        return $this->belongsTo(Nombre::class, 'nombre_id');
    }
}
