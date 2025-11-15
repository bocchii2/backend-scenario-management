<?php

namespace App\Http\Controllers;

use App\Models\Tarifas;
use Illuminate\Http\Request;

class TarifasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tarifas = Tarifas::all();
        return response()->json(['message'=> 'Lista de tarifas', 'data' => $tarifas]);
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
            'horario_id' => 'required|exists:horarios,id',
            'descripcion' => 'required|string|max:255',
            'precio_hora' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
        ]);
        $tarifa = Tarifas::create($request->all());
        return response()->json(['message'=> 'Tarifa creada', 'data' => $tarifa], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarifas $tarifas, $tarifa_id)
    {
        //
        $tarifa = Tarifas::find($tarifa_id);
        if (!$tarifa) {
            return response()->json(['message'=> 'Tarifa no encontrada'], 404);
        }
        return response()->json(['message'=> 'Detalle de tarifa', 'data' => $tarifa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarifas $tarifas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarifas $tarifas, $tarifa_id)
    {
        //
        $tarifa = Tarifas::find($tarifa_id);
        if (!$tarifa) {
            return response()->json(['message'=> 'Tarifa no encontrada'], 404);
        }
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'descripcion' => 'required|string|max:255',
            'precio_hora' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
        ]);
        $tarifa->update($request->all());
        return response()->json(['message'=> 'Tarifa actualizada', 'data' => $tarifa]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarifas $tarifas, $tarifa_id)
    {
        //
        $tarifa = Tarifas::find($tarifa_id);
        if (!$tarifa) {
            return response()->json(['message'=> 'Tarifa no encontrada'], 404);
        }
        $tarifa->delete();
        return response()->json(['message'=> 'Tarifa eliminada']);
    }
}
