<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::with(['roles', 'departamento', 'cargos'])->get();
        return response()->json($usuarios, 200);
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
        //
        $request->validate([
            "nombres_completos"=> "required|string|max:255",
            "correo_electronico"=> "required|email|max:255|unique:usuarios",
            "tipo_identificacion"=> "required|string|max:50",
            "identificacion"=> "required|string|max:50|unique:usuarios",
            "telefono"=> "required|string|max:50",
            "password"=> "required|string|min:8|confirmed",
            "activo"=> "required|boolean",
            "cargo_id"=> "required|exists:cargos,id",
            "departamento_id"=> "required|exists:departamentos,id",
        ]);
        $usuario = Usuario::create($request->all());
        return response()->json(["message" => "Usuario creado exitosamente.", "usuario" => $usuario], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($usuario_id)
    {
        $usuario = Usuario::with('cargo', 'departamento')->findOrFail($usuario_id);
        return response()->json(["usuario" => $usuario], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $request->validate([
            "nombres_completos"=> "sometimes|string|max:255",
            "correo_electronico"=> "sometimes|email|max:255|unique:usuarios,correo_electronico,".$usuario->id,
            "tipo_identificacion"=> "sometimes|string|max:50",
            "identificacion"=> "sometimes|string|max:50|unique:usuarios,identificacion,".$usuario->id,
            "telefono"=> "sometimes|string|max:50",
            "password"=> "nullable|string|min:8|confirmed",
            "activo"=> "sometimes|boolean",
            "cargos"=> "sometimes|array", // Array con ids de los cargos
            "cargos.*"=> "exists:cargos,id",
            "roles"=> "sometimes|array", // Array con ids de los roles
            "roles.*"=> "exists:roles,id",
            "departamento_id"=> "sometimes|exists:departamentos,id",
        ]);

        // Actualizar campos escalares
        $datosActualizar = $request->except(['cargos', 'roles']);
        
        if ($datosActualizar) {
            $usuario->update($datosActualizar);
        }

        // Sincronizar cargos si se proporcionan
        if ($request->has('cargos')) {
            $usuario->cargos()->sync($request->cargos);
        }

        // Sincronizar roles si se proporcionan
        if ($request->has('roles')) {
            $usuario->roles()->sync($request->roles);
        }

        // Recargar usuario con relaciones
        $usuario->load(['cargos', 'roles', 'departamento']);

        return response()->json(["message" => "Usuario actualizado exitosamente.", "usuario" => $usuario], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $usuario->delete();
        return response()->json(["message" => "Usuario eliminado exitosamente."], 204);
    }

    public function getRoles($usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $roles = $usuario->roles;
        return response()->json(["message" => "Roles del usuario", "roles" => $roles], 200);
    }

    /**
     * Obtener un rol específico del usuario.
     * GET /api/usuarios/{usuario_id}/roles/{role_id}
     */
    public function getRoleById($usuario_id, $role_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $role = $usuario->roles()->where('roles.id', $role_id)->first();

        if (!$role) {
            return response()->json(["message" => "El usuario no tiene este rol"], 404);
        }

        return response()->json(["message" => "Rol encontrado", "role" => $role], 200);
    }

    /**
     * Obtener todos los cargos del usuario.
     * GET /api/usuarios/{usuario_id}/cargos
     */
    public function getCargos($usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $cargos = $usuario->cargos;
        return response()->json(["message" => "Cargos del usuario", "cargos" => $cargos], 200);
    }

    /**
     * Obtener un cargo específico del usuario.
     * GET /api/usuarios/{usuario_id}/cargos/{cargo_id}
     */
    public function getCargoById($usuario_id, $cargo_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $cargo = $usuario->cargos()->where('cargos.id', $cargo_id)->first();

        if (!$cargo) {
            return response()->json(["message" => "El usuario no tiene este cargo"], 404);
        }

        return response()->json(["message" => "Cargo encontrado", "cargo" => $cargo], 200);
    }

    /**
     * Verificar si el usuario tiene un rol específico.
     * GET /api/usuarios/{usuario_id}/tiene-rol/{role_id}
     */
    public function hasRole($usuario_id, $role_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $tiene = $usuario->roles()->where('roles.id', $role_id)->exists();

        return response()->json([
            "message" => "Verificación de rol",
            "usuario_id" => $usuario_id,
            "role_id" => $role_id,
            "tiene_rol" => $tiene,
        ], 200);
    }

    /**
     * Verificar si el usuario tiene un cargo específico.
     * GET /api/usuarios/{usuario_id}/tiene-cargo/{cargo_id}
     */
    public function hasCargo($usuario_id, $cargo_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $tiene = $usuario->cargos()->where('cargos.id', $cargo_id)->exists();

        return response()->json([
            "message" => "Verificación de cargo",
            "usuario_id" => $usuario_id,
            "cargo_id" => $cargo_id,
            "tiene_cargo" => $tiene,
        ], 200);
    }

   public function assignRole(Request $request, $usuario_id)
   {
       $request->validate([
           'role_id' => 'required|exists:roles,id',
       ]);

       $usuario = Usuario::findOrFail($usuario_id);
       $usuario->roles()->attach($request->role_id);

       return response()->json(['message' => 'Rol asignado al usuario exitosamente.'], 200);
   }

    /**
     * Asignar múltiples roles al usuario (sync sin detach).
     * POST /api/usuarios/{usuario_id}/assign-roles
     */
    public function assignRoles(Request $request, $usuario_id)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $usuario = Usuario::findOrFail($usuario_id);
        $usuario->roles()->syncWithoutDetaching($request->roles);

        $usuario->load('roles');

        return response()->json([
            'message' => 'Roles asignados exitosamente',
            'usuario_id' => $usuario_id,
            'roles' => $usuario->roles,
        ], 200);
    }

    /**
     * Asignar un cargo al usuario.
     * POST /api/usuarios/{usuario_id}/assign-cargo
     */
    public function assignCargo(Request $request, $usuario_id)
    {
        $request->validate([
            'cargo_id' => 'required|exists:cargos,id',
        ]);

        $usuario = Usuario::findOrFail($usuario_id);
        $usuario->cargos()->attach($request->cargo_id);

        return response()->json(['message' => 'Cargo asignado al usuario exitosamente.'], 200);
    }

    /**
     * Asignar múltiples cargos al usuario (sync sin detach).
     * POST /api/usuarios/{usuario_id}/assign-cargos
     */
    public function assignCargos(Request $request, $usuario_id)
    {
        $request->validate([
            'cargos' => 'required|array',
            'cargos.*' => 'exists:cargos,id',
        ]);

        $usuario = Usuario::findOrFail($usuario_id);
        $usuario->cargos()->syncWithoutDetaching($request->cargos);

        $usuario->load('cargos');

        return response()->json([
            'message' => 'Cargos asignados exitosamente',
            'usuario_id' => $usuario_id,
            'cargos' => $usuario->cargos,
        ], 200);
    }

    /**
     * Remover un rol del usuario.
     * DELETE /api/usuarios/{usuario_id}/roles/{role_id}
     */
    public function removeRole($usuario_id, $role_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $usuario->roles()->detach($role_id);

        return response()->json(['message' => 'Rol removido del usuario exitosamente.'], 200);
    }

    /**
     * Remover un cargo del usuario.
     * DELETE /api/usuarios/{usuario_id}/cargos/{cargo_id}
     */
    public function removeCargo($usuario_id, $cargo_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);
        $usuario->cargos()->detach($cargo_id);

        return response()->json(['message' => 'Cargo removido del usuario exitosamente.'], 200);
    }


    /**
     * Subir imagen de perfil del usuario.
     * POST /api/usuarios/{usuario_id}/upload-profile-image
     */
    public function uploadProfileImage(Request $request, $usuario_id)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $usuario = Usuario::findOrFail($usuario_id);

        // Eliminar imagen antigua si existe
        if ($usuario->profile_image && \Storage::disk('public')->exists($usuario->profile_image)) {
            \Storage::disk('public')->delete($usuario->profile_image);
        }

        // Guardar nueva imagen
        $path = $request->file('profile_image')->store('profile-images', 'public');

        $usuario->update(['profile_image' => $path]);

        return response()->json([
            'message' => 'Imagen de perfil subida exitosamente',
            'profile_image_url' => $usuario->profile_image_url,
            'usuario' => $usuario,
        ], 200);
    }

    /**
     * Obtener imagen de perfil del usuario.
     * GET /api/usuarios/{usuario_id}/profile-image
     */
    public function getProfileImage($usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);

        if (!$usuario->profile_image) {
            return response()->json(['message' => 'El usuario no tiene imagen de perfil'], 404);
        }

        return response()->json([
            'profile_image_url' => $usuario->profile_image_url,
            'profile_image' => $usuario->profile_image,
        ], 200);
    }

    /**
     * Eliminar imagen de perfil del usuario.
     * DELETE /api/usuarios/{usuario_id}/profile-image
     */
    public function deleteProfileImage($usuario_id)
    {
        $usuario = Usuario::findOrFail($usuario_id);

        if ($usuario->profile_image) {
            \Storage::disk('public')->delete($usuario->profile_image);
            $usuario->update(['profile_image' => null]);
        }

        return response()->json(['message' => 'Imagen de perfil eliminada exitosamente'], 200);
    }
}
