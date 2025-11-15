<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventosInternos extends Model
{
    //
    protected $table = "eventos_internos";
    protected $fillable = [
        "horario_id",
        "tipo_evento_interno_id",
        "nombre_evento",
        "evento_privado",
        "descripcion",
        "fecha_evento",
        "departamento_id",
        "created_at",
        "updated_at"
    ];

    public function horario()
    {
        return $this->belongsTo(Horarios::class, 'horario_id');
    }

    public function tipoEventoInterno()
    {
        return $this->belongsTo(TiposEventosInternos::class, 'tipo_evento_interno_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

}
