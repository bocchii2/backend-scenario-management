<?php

namespace App\Http\Controllers;

use App\Models\TiposEventosInternos;
use Illuminate\Http\Request;

class TiposEventosInternosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tiposEventosInternos = TiposEventosInternos::all();
        return response()->json([
            'message' => 'Tipos de eventos internos obtenidos',
            'data' => $tiposEventosInternos
        ], 200);
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
            'nombre_tipo_evento'=> 'string|required',
            'descripcion' => 'string|required',
            'activo' => 'boolean|nullable'
        ]);
        $eventosInternos = TiposEventosInternos::create($request->all());
        return response()->json([
            'message'=> 'Tipo de evento interno creado con exito',
            'data'=> $eventosInternos
            ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposEventosInternos $tiposEventosInternos, $id)
    {
        //
        $eventosInternos = TiposEventosInternos::find($id);
        if (!$eventosInternos) {
            return response()->json([
                'message' => 'Tipo de evento interno no encontrado'
            ], 404);
        }
        return response()->json([
            'message' => 'Tipo de evento interno obtenido con exito',
            'data' => $eventosInternos
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiposEventosInternos $tiposEventosInternos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TiposEventosInternos $tiposEventosInternos, $id)
    {
        //
        $eventosInternos = TiposEventosInternos::find($id);
        if (!$eventosInternos) {
            return response()->json([
                'message' => 'Tipo de evento interno no encontrado'
            ], 404);
        }
        $request->validate([
            'nombre_tipo_evento'=> 'string|required',
            'descripcion' => 'string|required',
            'activo' => 'boolean|nullable'
        ]);
        $eventosInternos->update($request->all());
        return response()->json([
            'message'=> 'Tipo de evento interno actualizado con exito',
            'data'=> $eventosInternos
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiposEventosInternos $tiposEventosInternos, $id)
    {
        //
        $eventosInternos = TiposEventosInternos::find($id);
        if (!$eventosInternos) {
            return response()->json([
                'message' => 'Tipo de evento interno no encontrado'
            ], 404);
        }
        $eventosInternos->delete();
        return response()->json([
            'message' => 'Tipo de evento interno eliminado con exito'
        ], 200);
    }
}
