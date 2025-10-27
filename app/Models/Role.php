<?php
// filepath: app/Models/Role.php
// ...existing code...


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Role extends Model
{
    use Auditable;
    protected $table = "roles";
    protected $fillable = ["nombre", "descripcion", "slug", 'created_by', 'updated_by'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
            ->withTimestamps();
    }

    // Añadir relación a usuarios (pivot role_usuario)
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'role_usuario', 'role_id', 'usuario_id')
            ->withTimestamps();
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

    // Normaliza input (id o modelo) y asigna permiso
    public function assignPermission($permission)
    {
        if ($permission instanceof Permission) {
            $permissionId = $permission->id;
        } elseif (is_numeric($permission)) {
            $permissionId = (int) $permission;
            Permission::findOrFail($permissionId); // valida existencia
        } else {
            throw new \InvalidArgumentException('Permission must be id or Permission model.');
        }

        return $this->permissions()->syncWithoutDetaching([$permissionId]);
    }

    public function assignPermissions(array $permissions) {
        foreach ($permissions as $permission_id) {
            $this->assignPermission($permission_id);
        }
    }

    public function removePermission($permission)
    {
        if (is_numeric($permission)) {
            $permission = Permission::findOrFail($permission);
        }

        return $this->permissions()->detach($permission->id);
    }

    public function syncPermissions(array $permissions)
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            return is_numeric($permission) ? $permission : $permission->id;
        })->toArray();

        return $this->permissions()->sync($permissionIds);
    }

    // Corregido: buscar por id de permiso en la tabla permissions
    public function hasPermission($permissionId)
    {
        return $this->permissions()->where('permissions.id', $permissionId)->exists();
    }
}
// ...existing code...