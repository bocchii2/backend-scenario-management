<?php

namespace App\Http\Controllers;

use App\Models\DepartamentoCategoria;
use Illuminate\Http\Request;

class DepartamentoCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoria = DepartamentoCategoria::all();
        return response()->json(['message' => 'Categorias retrieved successfully', 'data' => $categoria], 200);
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
        $request->validate( [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        DepartamentoCategoria::create([
            'nombre'=> $request->nombre,
            'descripcion'=> $request->descripcion,
            'activo'=> true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($departamentoCategoriaId)
    {
        //
        $categoria = DepartamentoCategoria::findOrFail($departamentoCategoriaId);
        return response()->json(['message' => 'Categoria retrieved successfully', 'data' => $categoria], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DepartamentoCategoria $departamentoCategoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $departamentoCategoriaId)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = DepartamentoCategoria::findOrFail($departamentoCategoriaId);
        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json(['message' => 'Categoria updated successfully', 'data' => $categoria], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($departamentoCategoriaId)
    {
        $categoria = DepartamentoCategoria::findOrFail($departamentoCategoriaId);
        $categoria->delete();

        return response()->json(['message' => 'Categoria deleted successfully', 'data' => $categoria], 200);
    }
}
