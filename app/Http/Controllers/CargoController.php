<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cargo = Cargo::all();
        return response()->json(['message' => 'Cargos retrieved successfully', 'data' => $cargo], 200);
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
            'nombre_cargo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $cargo = Cargo::create([
            'nombre_cargo' => $request->nombre_cargo,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json(['message' => 'Cargo created successfully', 'data' => $cargo], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($cargo_id)
    {
        $cargo = Cargo::findOrFail($cargo_id);
        return response()->json(['message' => 'Cargo retrieved successfully', 'data' => $cargo], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cargo_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cargo_id)
    {
        //
        $request->validate([
            'nombre_cargo' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|nullable|string|max:255',
        ]);
        $cargo = Cargo::findOrFail($cargo_id);
        $cargo->update($request->only('nombre_cargo', 'descripcion'));
        return response()->json($cargo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cargo_id)
    {
        //
        Cargo::findOrFail($cargo_id)->delete();
        return response()->json(['message' => 'Cargo deleted successfully'], 200);
    }
}
