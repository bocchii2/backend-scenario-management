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

    /**
     * Obtener todos los ancestros de un departamento.
     * GET /api/departamentos/{id}/ancestros
     */
    public function obtenerAncestros($departamento_id)
    {
        $departamento = Departamento::findOrFail($departamento_id);
        $ancestros = $departamento->obtenerAncestros();

        return response()->json([
            'message' => 'Ancestros del departamento obtenidos',
            'departamento_id' => $departamento->id,
            'data' => $ancestros,
        ], 200);
    }

    /**
     * Obtener la ruta completa de un departamento (para breadcrumbs).
     * GET /api/departamentos/{id}/ruta
     */
    public function obtenerRuta($departamento_id)
    {
        $departamento = Departamento::findOrFail($departamento_id);
        $ruta = $departamento->obtenerRuta();

        return response()->json([
            'message' => 'Ruta del departamento obtenida',
            'departamento_id' => $departamento->id,
            'data' => $ruta,
        ], 200);
    }

    /**
     * Obtener todos los descendientes de un departamento.
     * GET /api/departamentos/{id}/descendientes
     */
    public function obtenerDescendientes($departamento_id)
    {
        $departamento = Departamento::findOrFail($departamento_id);
        $descendientes = $departamento->obtenerDescendientes();

        return response()->json([
            'message' => 'Descendientes del departamento obtenidos',
            'departamento_id' => $departamento->id,
            'count' => $descendientes->count(),
            'data' => $descendientes,
        ], 200);
    }

    /**
     * Verificar si un departamento es ancestro de otro.
     * GET /api/departamentos/{id}/es-ancestro/{otro_id}
     */
    public function esAncestroDE($departamento_id, $otro_id)
    {
        $departamento = Departamento::findOrFail($departamento_id);
        $otroDepartamento = Departamento::findOrFail($otro_id);
        $esAncestro = $departamento->esAncestroDE($otroDepartamento);

        return response()->json([
            'message' => '¿Es ancestro?',
            'departamento_id' => $departamento_id,
            'otro_departamento_id' => $otro_id,
            'es_ancestro' => $esAncestro,
        ], 200);
    }

    /**
     * Obtener el departamento raíz de la jerarquía.
     * GET /api/departamentos/{id}/raiz
     */
    public function obtenerRaiz($departamento_id)
    {
        $departamento = Departamento::findOrFail($departamento_id);
        $raiz = $departamento->obtenerRaiz();

        return response()->json([
            'message' => 'Departamento raíz obtenido',
            'departamento_id' => $departamento->id,
            'data' => $raiz,
        ], 200);
    }
}
