<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Espacio extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'espacios';

    protected $fillable = [
        'nombre_espacio',
        'capacidad',
        'descripcion',
        'metro_cuadrado',
        'pies_cuadrados',
        'altura',
        'otros_atributos',
        'atributos_capacidad',
        'categoria_espacio_id',
        'departamento_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'otros_atributos' => 'array',
        'atributos_capacidad' => 'array',
        'capacidad' => 'integer',
        'metro_cuadrado' => 'integer',
        'pies_cuadrados' => 'integer',
        'altura' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(CategoriaEspacio::class, 'categoria_espacio_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
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

    // Scopes útiles
    public function scopeByDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    public function scopeConCapacidadMinima($query, int $min)
    {
        return $query->where('capacidad', '>=', $min);
    }

    // Accessor / Mutator de ejemplo
    public function getNombreEspacioAttribute($value)
    {
        return ucfirst($value);
    }

    public function setNombreEspacioAttribute($value)
    {
        $this->attributes['nombre_espacio'] = trim($value);
    }

    // Métodos de negocio
    public function isAvailable(\DateTimeInterface $from = null, \DateTimeInterface $to = null): bool
    {
        // Implementa según tus reglas / reservas; aquí un placeholder
        // return !$this->reservas()->between($from, $to)->exists();
        return true;
    }

    public function capacidadDescripcion(): string
    {
        return $this->capacidad ? (string) $this->capacidad : 'No especificada';
    }
}