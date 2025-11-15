<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposEventosInternos extends Model
{
    //
    protected $table = "tipos_eventos_internos";

    protected $fillable = [
        "nombre_tipo_evento",
        "descripcion",
        "activo",
        "created_at",
        "updated_at",
    ];

    public function eventosInternos()
    {
        return $this->hasMany("eventos_internos","tipo_evento_interno_id","id");
    }
}
