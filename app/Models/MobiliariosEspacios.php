<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobiliariosEspacios extends Model
{
    protected $table = 'mobiliarios_espacios';
    protected $fillable = ['mobiliario_id', 'espacio_id'];
    public $timestamps = true;

    /**
     * Relación hacia Mobiliarios
     */
    public function mobiliario()
    {
        return $this->belongsTo(Mobiliarios::class, 'mobiliario_id');
    }

    /**
     * Relación hacia Espacios
     */
    public function espacio()
    {
        return $this->belongsTo(Espacio::class, 'espacio_id');
    }
}
