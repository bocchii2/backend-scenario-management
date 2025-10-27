<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    protected $table = "usuarios";
    protected $fillable = ["nombres_completos", "correo_electronico", "tipo_identificacion", "identificacion", "telefono", "password", "activo", "cargo_id", "departamento_id", 'created_by', 'updated_by'];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    // Añadir relación roles (pivot role_usuario)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_usuario', 'usuario_id', 'role_id')
            ->withTimestamps()->with('permissions');
    }

    /**
     * Obtener el identificador que se usará en el token (normalmente la PK).
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retornar claims personalizados a añadir al payload del token.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }


}