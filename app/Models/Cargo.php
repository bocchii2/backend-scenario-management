<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Cargo extends Model
{
    //
    use Auditable;
        protected $table = "cargos";
    protected $fillable = ['nombre_cargo', 'descripcion', 'created_by', 'updated_by'];

    
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'cargo_id');
    }

    // Auditoría: usuario que creó / actualizó
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function actualizador()
    {
        return $this->belongsTo(Usuario::class, 'updated_by');
    }


}
