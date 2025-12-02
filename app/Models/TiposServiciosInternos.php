<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposServiciosInternos extends Model
{
    //
    protected $table = "tipos_servicios_internos";
    protected $fillable = ["nombre_tipo_servicio","descripcion","estado"];

    public $timestamps = true;


    public function serviciosInternos()
    {
        return $this->hasMany(ServicioInterno::class, 'tipo_servicio_interno_id');
    }
}