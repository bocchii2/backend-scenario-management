<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = "permissions";
    // Definición de la tabla
    protected $fillable = ['entidad', 'accion', 'nombre_completo'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission')
        ->withTimestamps(); // Si tienes timestamps en la tabla pivote
    }
}
