<?php

namespace App\Http\Controllers;

use App\Models\TiposServicios;
use Illuminate\Http\Request;

class TiposServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tiposServicios = TiposServicios::all();
        return response()->json(["data" => $tiposServicios, "message" => "Tipos de servicios obtenidos exitosamente"], 200);
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

        $tiposServicios = TiposServicios::create($request->all());
        return response()->json(["data" => $tiposServicios, "message" => "Tipo de servicio creado exitosamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposServicios $tiposServicios, $id)
    {
        //
        $tiposServicios = TiposServicios::find($id);
        if (!$tiposServicios) {
            return response()->json(["message" => "Tipo de servicio no encontrado"], 404);
        }
        return response()->json(["data" => $tiposServicios, "message" => "Tipo de servicio obtenido exitosamente"], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiposServicios $tiposServicios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_tipo_servicio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $tiposServicios = TiposServicios::find($id);
        if (!$tiposServicios) {
            return response()->json(["message" => "Tipo de servicio no encontrado"], 404);
        }

        $tiposServicios->update($request->all());
        return response()->json(["data" => $tiposServicios, "message" => "Tipo de servicio actualizado exitosamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiposServicios $tiposServicios, $id)
    {
        $tiposServicios = TiposServicios::find($id);
        if (!$tiposServicios) {
            return response()->json(["message" => "Tipo de servicio no encontrado"], 404);
        }

        $tiposServicios->delete();
    }
}
