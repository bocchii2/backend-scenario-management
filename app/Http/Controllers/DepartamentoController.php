<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // traer con departamento padre y categoria
        $departamento = Departamento::with('departamentoPadre', 'categoria')->get();
        return response()->json(['message' => 'Lista de departamentos', 'data' => $departamento], 200);
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
        $validate = $request->validate([
            'nombre_departamento' => 'required|string|max:255',
            'nomenclatura' => 'required|string|max:50',
            'departamento_padre_id' => 'nullable|exists:departamentos,id',
            'departamento_categoria_id' => 'required|exists:departamento_categorias,id',
            'activo' => 'required|boolean',
        ]);

        $departamento = Departamento::create($request->all());
        return response()->json(['message' => 'Departamento creado', 'data' => $departamento], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($departamento_id)
    {
        //
        $departamento = Departamento::findOrFail($departamento_id);
        $departamento->load('departamentoPadre');
        return response()->json(['message' => 'Departamento encontrado', 'data' => $departamento], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $departamento_id)
    {
        //
        $validate = $request->validate([
            'nombre_departamento' => 'sometimes|required|string|max:255',
            'nomenclatura' => 'sometimes|required|string|max:50',
            'departamento_padre_id' => 'sometimes|nullable|exists:departamentos,id',
            'activo' => 'sometimes|required|boolean',
        ]);
        $departamento = Departamento::findOrFail($departamento_id);
        $departamento->update($request->all());
        return response()->json(['message' => 'Departamento actualizado', 'data' => $departamento], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($departamento_id)
    {
        //
        $departamento = Departamento::findOrFail($departamento_id);
        $departamento->delete();
        return response()->json(['message' => 'Departamento eliminado'], 200);
    }

    public function getDepartamentoPadre($departamento_id) {
        $departamento_padre = Departamento::findOrFail($departamento_id)->departamentoPadre;
        return response()->json(['message' => 'Departamento padre obtenido', 'data' => $departamento_padre], 200);
    }


    public function getUsuariosDepartamento($departamento_id) {
        $usuarios = Departamento::findOrFail($departamento_id)->usuarios;
        return response()->json(['message' => 'Usuarios del departamento obtenidos', 'data' => $usuarios], 200);
    }
}
