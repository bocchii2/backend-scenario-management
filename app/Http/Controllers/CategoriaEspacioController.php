<?php

namespace App\Http\Controllers;

use App\Models\CategoriaEspacio;
use Illuminate\Http\Request;

class CategoriaEspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categoriaEspacio = CategoriaEspacio::with('espacios')->paginate(15);
        return response()->json(["message" => "Categorías de Espacios obtenidas con éxito", "data" => $categoriaEspacio]);
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
        ]);
        $categoriaEspacio = CategoriaEspacio::create([
            'nombre_categoria' => $request->nombre_categoria,
        ]);
        return response()->json(['message'=> 'Categoría de Espacio creada con éxito', 'data' => $categoriaEspacio]);
    }

    /**
     * Display the specified resource.
     */
    public function show($categoria_id)
    {
        $categoriaEspacio = CategoriaEspacio::with('espacios')->find($categoria_id);
        if (!$categoriaEspacio) {
            return response()->json(['message'=> 'Categoría de Espacio no encontrada'],404);
        }
        return response()->json(["message" => "Categoría de Espacio obtenida con éxito", "data" => $categoriaEspacio]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($categoria_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $categoria_id)
    {
        $request->validate([
            'nombre_categoria' => 'required|string|max:255',
        ]);
        $categoriaEspacio = CategoriaEspacio::find($categoria_id);
        $categoriaEspacio->update([
            'nombre_categoria' => $request->nombre_categoria,
        ]);
        return response()->json(['message'=> 'Categoría de Espacio actualizada con éxito', 'data' => $categoriaEspacio]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categoria_id)
    {
        $categoriaEspacio = CategoriaEspacio::find($categoria_id);
        if ($categoriaEspacio) {
            $categoriaEspacio->delete();
            return response()->json(['message'=> 'Categoría de Espacio eliminada con éxito']);
        }
        return response()->json(['message'=> 'Categoría de Espacio no encontrada'], 404);
    }
}
