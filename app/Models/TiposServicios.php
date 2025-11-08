<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposServicios extends Model
{
    //
    protected $table = "tipos_servicios";
    protected $fillable = ["nombre_tipo_servicio", "descripcion", "estado"];

    public function serviciosInternos()
    {
        return $this->hasMany(ServicioInterno::class, 'tipo_servicio_id');
    }


}
