<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::with('permissions')->get();
        return response()->json(["message" => "Roles obtenidos exitosamente.", "data" => $roles], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
        ]);

        $role = Role::create($request->only('nombre', 'descripcion', 'slug'));

        return response()->json(["message" => "Rol creado exitosamente.", "data" => $role], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($role_id)
    {
        $role = Role::with("permissions")->findOrFail($role_id);
        return response()->json(["message" => "Rol encontrado exitosamente.", "data" => $role], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|nullable|string|max:255',
            'slug' => 'sometimes|required|string|max:255' . $role->id,
        ]);
        $role->update($request->only('nombre', 'descripcion', 'slug'));
        return response()->json(["message" => "Rol actualizado exitosamente.", "data" => $role], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($role_id)
    {
        //
        $role = Role::findOrFail($role_id);
        $role->delete();

        return response()->json(["message" => "Rol eliminado exitosamente."], 204);
    }

     public function assignPermission(Request $request, $roleId)
    {
        $request->validate(['permission_id' => 'required|exists:permissions,id']);

        $role = Role::findOrFail($roleId);
        $role->assignPermission($request->permission_id);

        return response()->json(["message" => "Permiso asignado correctamente."], 200);
    }

     public function removePermission(Request $request, $roleId)
    {
        $request->validate(['permission_id' => 'required|exists:permissions,id']);

        $role = Role::findOrFail($roleId);
        $role->removePermission($request->permission_id);

        return response()->json(["message" => "Permiso removido correctamente."], 200);
    }

        public function syncPermissions(Request $request, $roleId)
    {
        $request->validate(['permissions' => 'required|array']);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permissions);

        return response()->json(["message" => "Permisos sincronizados correctamente."], 200);
    }

    public function hasPermission($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $hasPermission = $role->hasPermission($permissionId);
        if ($hasPermission) {
            return response()->json(["has_permission" => $hasPermission], 200);

        }

        return response()->json(["message"=> "No se encontró el permiso."], 403);

    }

    /**
     * Trae todos los roles con sus permisos (eager loading).
     */
    public function indexWithPermissions()
    {
        $roles = Role::with('permissions')->get();
        return response()->json(["message" => "Roles con permisos obtenidos exitosamente.", "roles" => $roles], 200);
    }

    /**
     * Trae un rol específico con sus permisos.
     */
    public function showWithPermissions($role_id)
    {
        $role = Role::with('permissions')->findOrFail($role_id);
        return response()->json(["message" => "Rol con permisos obtenido exitosamente.", "role" => $role], 200);
    }

}
