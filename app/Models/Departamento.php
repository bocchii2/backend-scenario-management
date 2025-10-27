<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Departamento extends Model
{
    use Auditable;
    //
    protected $table = "departamentos";
    protected $fillable = ['nombre_departamento', 'nomenclatura', 'departamento_padre_id', 'activo', 'created_by', 'updated_by'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'departamento_id');
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

    /*
    * Get the parent department
    */
    public function departamentoPadre()
    {
        return $this->belongsTo(Departamento::class, 'departamento_padre_id');
    }

    public function categoria(){
        return $this->belongsTo(DepartamentoCategoria::class,'departamento_categoria_id');
    }

    // Departamentos hijos
    public function departamentosHijos()
    {
        return $this->hasMany(Departamento::class, 'departamento_padre_id');
    }

    /*
    * Traer recursivamente todos los departamentos ancestros de un departamento
    */
    public function departamentosAncestros($departamentoID)
    {
        $departamento = Departamento::findOrFail($departamentoID);
        $ancestros = collect();

        while ($departamento && $departamento->departamentoPadre) {
            $departamento = $departamento->departamentoPadre;
            $ancestros->push($departamento);
        }

        return $ancestros;
    }
}
