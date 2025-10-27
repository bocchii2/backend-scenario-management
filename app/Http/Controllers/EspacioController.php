<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;

class EspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Espacio::with(['categoria', 'departamento'])->paginate(15);
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
            'nombre_espacio' => 'required|string|max:255',
            'capacidad' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'metro_cuadrado' => 'nullable|integer',
            'pies_cuadrados' => 'nullable|integer',
            'altura' => 'nullable|integer',
            'otros_atributos' => 'nullable|array',
            'atributos_capacidad' => 'nullable|array',
            'categoria_espacio_id' => 'required|exists:categoria_espacios,id',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $espacio = Espacio::create($data);

        return response()->json($espacio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Espacio $espacio)
    {
        return $espacio->load(['categoria', 'departamento']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Espacio $espacio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $espacio_id)
    {
        $espacio = Espacio::findOrFail($espacio_id);
        $data = $request->validate([
            'nombre_espacio' => 'sometimes|required|string|max:255',
            'capacidad' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'metro_cuadrado' => 'nullable|integer',
            'pies_cuadrados' => 'nullable|integer',
            'altura' => 'nullable|integer',
            'otros_atributos' => 'nullable|array',
            'atributos_capacidad' => 'nullable|array',
            'categoria_espacio_id' => 'sometimes|required|exists:categoria_espacios,id',
            'departamento_id' => 'sometimes|required|exists:departamentos,id',
        ]);

        $espacio->update($data);

        return response()->json(["message" => "Espacio actualizado correctamente", "data" => $espacio]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($espacio_id)
    {
        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->delete();
        return response()->json(["message" => "Espacio eliminado correctamente"], 204);
    }
}
