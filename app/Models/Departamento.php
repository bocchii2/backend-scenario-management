<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Departamento extends Model
{
    use Auditable;
    //
    protected $table = "departamentos";
    protected $fillable = ['nombre_departamento','departamento_categoria_id', 'nomenclatura', 'departamento_padre_id', 'activo', 'created_by', 'updated_by'];

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

    /**
     * Obtener todos los ancestros (padres, abuelos, etc.) de un departamento.
     * Retorna una colección ordenada desde el padre inmediato hasta la raíz.
     */
    public function obtenerAncestros()
    {
        $ancestros = collect();
        $departamento = $this;

        while ($departamento->departamentoPadre) {
            $departamento = $departamento->departamentoPadre;
            $ancestros->push($departamento);
        }

        return $ancestros;
    }

    /**
     * Obtener la ruta completa del departamento (raíz -> ... -> padre -> este).
     * Útil para breadcrumbs o mostrar jerarquía visual.
     */
    public function obtenerRuta()
    {
        $ruta = collect([$this]);
        $ancestros = $this->obtenerAncestros();
        
        // Invertir ancestros para que esté en orden: raíz -> ... -> padre
        return $ancestros->reverse()->merge($ruta);
    }

    /**
     * Obtener todos los descendientes (hijos, nietos, etc.) recursivamente.
     * Útil para operaciones en cascada.
     */
    public function obtenerDescendientes()
    {
        $descendientes = collect();

        foreach ($this->departamentosHijos as $hijo) {
            $descendientes->push($hijo);
            $descendientes = $descendientes->merge($hijo->obtenerDescendientes());
        }

        return $descendientes;
    }

    /**
     * Verificar si este departamento es ancestro de otro.
     */
    public function esAncestroDE($otroDepartamento)
    {
        return $otroDepartamento->obtenerAncestros()
            ->pluck('id')
            ->contains($this->id);
    }

    /**
     * Obtener el departamento raíz de la jerarquía.
     */
    public function obtenerRaiz()
    {
        $departamento = $this;

        while ($departamento->departamentoPadre) {
            $departamento = $departamento->departamentoPadre;
        }

        return $departamento;
    }
}
