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
        $usuarios = Usuario::with(['roles', 'departamento', 'cargo'])->get();
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
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            "nombres_completos"=> "sometimes|string|max:255",
            "correo_electronico"=> "sometimes|email|max:255|unique:usuarios,correo_electronico,".$usuario->id,
            "tipo_identificacion"=> "sometimes|string|max:50",
            "identificacion"=> "sometimes|string|max:50|unique:usuarios,identificacion,".$usuario->id,
            "telefono"=> "sometimes|string|max:50",
            "password"=> "nullable|string|min:8|confirmed",
            "activo"=> "sometimes|boolean",
            "cargo_id"=> "sometimes|exists:cargos,id",
            "departamento_id"=> "sometimes|exists:departamentos,id",
        ]);

        $usuario->update($request->all());
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
        return response()->json(["roles" => $roles], 200);
    }
}
