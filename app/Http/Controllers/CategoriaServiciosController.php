<?php

namespace App\Http\Controllers;

use App\Models\CategoriaServicios;
use Illuminate\Http\Request;

class CategoriaServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = CategoriaServicios::all()->load("servicios");
        return response()->json($data, 200);
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
            'nombre_categoria' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        CategoriaServicios::create($request->all());
        return response()->json(['message' => 'CategoriaServicios created successfully', 'data' => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoriaServicios $categoriaServicios, $id)
    {
        //
        $data = CategoriaServicios::find($id)->load("servicios");
        if (!$data) {
            return response()->json(["message"=> "Categoria no encontrada"],404);
        }
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaServicios $categoriaServicios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoriaServicios $categoriaServicios, $id)
    {
        //
        $request->validate([
            'nombre_categoria' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $categoria = CategoriaServicios::find($id);
        if (!$categoria) {
            return response()->json(['message' => 'Categoria no encontrada'], 404);
        }
        $data = CategoriaServicios::find($id)->update($request->all());
        return response()->json(['message' => 'CategoriaServicios updated successfully', 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoriaServicios $categoriaServicios, $id)
    {
        //
        $categoria = CategoriaServicios::find($id);
        if (!$categoria) {
            return response()->json(['message' => 'Categoria no encontrada'], 404);
        }
        $categoria->delete();
        return response()->json(['message' => 'CategoriaServicios deleted successfully'], 200);
    }
}
