<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEspacio extends Model
{
    //
    protected $table = "categoria_espacios";
    protected $fillable = ['nombre_categoria'];

    public function espacios()
    {
        return $this->hasMany(Espacio::class, 'categoria_espacio_id');
    }
}
