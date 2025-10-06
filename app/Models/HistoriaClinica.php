<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'fecha',
        'descripcion',
        'tipo',
    ];

    // Relación con la mascota
    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }
}
