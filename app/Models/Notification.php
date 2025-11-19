<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'persona_id',
        'title',
        'message',
        'read',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
