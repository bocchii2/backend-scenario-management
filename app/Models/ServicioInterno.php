<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioInterno extends Model
{
    //
    protected $table = "servicio_internos";

    protected $fillable = ["nombre_servicio","codigo_servicio", "activo", "created_at", "updated_at", "deleted_at"];

    public function espacios()
    {
        return $this->belongsToMany(Espacio::class, "servicio_interno_espacios", "servicio_interno_id", "espacio_id");
    }

}
