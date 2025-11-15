<?php

namespace App\Http\Controllers;

use App\Models\TipoEvento;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tipoEventos = TipoEvento::all();
        return response()->json(['message' => 'Lista de tipos de eventos', 'data' => $tipoEventos], 200);
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
            'nombre_tipo_evento' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $tipoEvento = TipoEvento::create([
            'nombre_tipo_evento' => $request->nombre_tipo_evento,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
        ]);

        return response()->json(['message' => 'Tipo de evento creado exitosamente', 'data' => $tipoEvento], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoEvento $tipoEvento, $eventoId)
    {
        //
        $tipoEvento = TipoEvento::find($eventoId);
        if (!$tipoEvento) {
            return response()->json(['message' => 'Tipo de evento no encontrado'], 404);
        }
        return response()->json(['message' => 'Detalle del tipo de evento', 'data' => $tipoEvento], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoEvento $tipoEvento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoEvento $tipoEvento, $eventoId)
    {
        //
        $tipoEvento = TipoEvento::find($eventoId);
        if (!$tipoEvento) {
            return response()->json(['message' => 'Tipo de evento no encontrado'], 404);
        }

        $request->validate([
            'nombre_tipo_evento' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'sometimes|required|boolean',
        ]);

        $tipoEvento->update($request->only(['nombre_tipo_evento', 'descripcion', 'estado']));

        return response()->json(['message' => 'Tipo de evento actualizado exitosamente', 'data' => $tipoEvento], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoEvento $tipoEvento, $eventoId)
    {
        //
        $tipoEvento = TipoEvento::find($eventoId);
        if (!$tipoEvento) {
            return response()->json(['message' => 'Tipo de evento no encontrado'], 404);

        }
        $tipoEvento->delete();
        return response()->json(['message' => 'Tipo de evento eliminado exitosamente'], 200);
    }
}
