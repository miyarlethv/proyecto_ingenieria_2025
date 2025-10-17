<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo
     */
    protected $table = 'historias_clinicas';

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'mascota_id',
        'fecha',
        'descripcion',
        'tipo',
    ];

    /**
     * Relación con el modelo Mascota
     * Una historia clínica pertenece a una mascota
     */
    public function mascota()
    {
        return $this->belongsTo(Mascota::class, 'mascota_id');
    }
}
