<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudAdopcion extends Model
{
    protected $table = 'solicitudes_adopcion';

    protected $fillable = [
        'mascota_id',
        'persona_id',
        'edad',
        'ciudad_residencia',
        'ocupacion',
        'estrato_social',
        'tiene_hijos',
        'numero_personas_hogar',
        'acepta_seguimiento',
        'estado'
    ];

    protected $casts = [
        'edad' => 'integer',
        'numero_personas_hogar' => 'integer',
    ];

    /**
     * Relación con el modelo Mascota
     */
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Relación con el modelo Persona
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }
}
