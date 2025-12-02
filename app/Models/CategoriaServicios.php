<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaServicios extends Model
{
    //
    protected $table = "categoria_servicios";
    protected $fillable = ["nombre_categoria", "descripcion", "activo"];

    public $timestamps = true;

    public function servicios()
    {
        return $this->hasMany(Servicios::class, 'categoria_servicio_id');
    }
}
