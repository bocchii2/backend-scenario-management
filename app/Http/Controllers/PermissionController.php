<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $permissions = Permission::all();
        return response()->json(['message' => 'Lista de permisos', 'data' => $permissions], 200);
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
        $permission = Permission::create($request->all());
        return response()->json($permission, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }

    public function getByEntidad(Request $request, $entidad)
    {
        // validar el parámetro de ruta
        $validator = \Illuminate\Support\Facades\Validator::make(
            ['entidad' => $entidad],
            ['entidad' => 'required|string|max:100']
        );

        if ($validator->fails()) {
            return response()->json(['message' => 'Entidad inválida', 'errors' => $validator->errors()], 422);
        }

        $entidad = trim($entidad);

        // Búsqueda exacta case-insensitive (Postgres). Para MySQL use LOWER(...) = ...
        $permissions = Permission::whereRaw('LOWER(entidad) = ?', [mb_strtolower($entidad)])->get();

        return response()->json(['message' => 'Lista de permisos', 'data' => $permissions], 200);
    }
}
