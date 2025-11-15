<?php

namespace App\Http\Controllers;

use App\Models\CategoriaMobiliarios;
use Illuminate\Http\Request;

class CategoriaMobiliariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoriaMobiliarios::with('mobiliarios')->paginate(15);
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
        $data = $request->validate([
            'nombre_categoria' => 'required|string|max:255|unique:categoria_mobiliarios,nombre_categoria',
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $data['activo'] = $data['activo'] ?? true;
        $categoria = CategoriaMobiliarios::create($data);

        return response()->json($categoria, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($categoria_id)
    {
        $categoria = CategoriaMobiliarios::with('mobiliarios')
            ->findOrFail($categoria_id);
        
        return response()->json($categoria);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoriaMobiliarios $categoriaMobiliarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $categoria_id)
    {
        $categoria = CategoriaMobiliarios::findOrFail($categoria_id);
        
        $data = $request->validate([
            'nombre_categoria' => 'sometimes|required|string|max:255|unique:categoria_mobiliarios,nombre_categoria,' . $categoria_id,
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $categoria->update($data);

        return response()->json([
            'message' => 'Categoría de Mobiliario actualizada correctamente',
            'data' => $categoria
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoria_id)
    {
        $categoria = CategoriaMobiliarios::findOrFail($categoria_id);
        $categoria->delete();
        
        return response()->json(['message' => 'Categoría de Mobiliario eliminada correctamente'], 204);
    }
}
