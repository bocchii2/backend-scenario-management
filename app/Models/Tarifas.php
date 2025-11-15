<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifas extends Model
{
    //
    protected $table = "tarifas";

    protected $fillable = [
        "horario_id",
        "descripcion",
        "precio_hora",
        "activo",
    ];

    public $timestamps = false;

    public function horario() 
    {
        return $this->belongsTo(Horarios::class);
    }

}
