<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaMobiliarios extends Model
{
    //
    protected $table = "categoria_mobiliarios";
    protected $fillable = ["nombre_categoria", "descripcion", "activo", "created_at", "updated_at"];

    public function mobiliarios()
    {
        return $this->hasMany(Mobiliarios::class, 'categoria_mobiliario_id');
    }
}


