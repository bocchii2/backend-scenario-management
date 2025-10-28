<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioInternoEspacios extends Model
{
    //
    protected $table = "servicio_interno_espacios";
    protected $fillable = ["servicio_interno_id", "espacio_id", "created_at", "updated_at"];
}
