<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class DepartamentoCategoria extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'departamento_categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // Una categoría tiene muchos departamentos
    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'departamento_categoria_id');
    }

    // Auditoría
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function actualizador()
    {
        return $this->belongsTo(Usuario::class, 'updated_by');
    }
}
