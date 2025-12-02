<?php

namespace App\Http\Controllers;

use App\Models\TiposServiciosInternos;
use Illuminate\Http\Request;

class TiposServiciosInternosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $servicios = TiposServiciosInternos::all()->load('serviciosInternos');

        return response()->json(['data' => $servicios, 'message' => 'Tipos de servicios internos obtenidos exitosamente'], 200);
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
            'nombre_tipo_servicio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $servicos = TiposServiciosInternos::create($request->all());
        return response()->json(['data'=> $servicos,'message'=> 'Tipo de servicio interno creado con exito'],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposServiciosInternos $tiposServiciosInternos, $id)
    {
        $tipoServicio = TiposServiciosInternos::find($id);
        if (!$tipoServicio) {
            return response()->json(['message' => 'Tipo de servicio interno no encontrado'], 404);
        }
        return response()->json(['data' => $tipoServicio->load('serviciosInternos')], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiposServiciosInternos $tiposServiciosInternos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        //
        $request->validate([
            'nombre_tipo_servicio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $servicos = TiposServiciosInternos::find($id);
        if (!$servicos) {
            return response()->json(['message' => 'Tipo de servicio interno no encontrado'], 404);
        }

        $servicos->update($request->all());
        return response()->json(['data' => $servicos, 'message' => 'Tipo de servicio interno actualizado con éxito'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $servicos = TiposServiciosInternos::find($id);
        if (!$servicos) {
            return response()->json(['message' => 'Tipo de servicio interno no encontrado'], 404);
        }

        $servicos->delete();
        return response()->json(['message' => 'Tipo de servicio interno eliminado con éxito'], 200);
    }
}
