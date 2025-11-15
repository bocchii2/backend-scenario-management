<?php

namespace App\Http\Controllers;

use App\Models\ServicioInterno;
use Illuminate\Http\Request;
use App\Models\Espacio;

class ServicioInternoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = ServicioInterno::all();
        return response()->json(['data' => $data, 'message' => 'Servicios internos obtenidos exitosamente'], 200);
        
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
            'nombre_servicio' => 'required|string|max:255',
            'codigo_servicio' => 'required|string|max:100|unique:servicio_internos,codigo_servicio',
            'activo' => 'required|boolean',
        ]);
        $servicioInterno = ServicioInterno::create($request->all());
        return response()->json(['message' => 'Servicio interno creado exitosamente', 'data' => $servicioInterno], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicioInterno $servicioInterno, $id)
    {
        $servicioInterno = ServicioInterno::find($id);
        if (!$servicioInterno) {
            return response()->json(['message' => 'Servicio interno no encontrado'], 404);
        }
        return response()->json(['data' => $servicioInterno], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServicioInterno $servicioInterno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServicioInterno $servicioInterno, $id)
    {
        $servicioInterno = ServicioInterno::find($id);
        if (!$servicioInterno) {
            return response()->json(['message' => 'Servicio interno no encontrado'], 404);
        }
        $request->validate([
            'nombre_servicio' => 'required|string|max:255',
            'codigo_servicio' => 'required|string|max:100|unique:servicio_internos,codigo_servicio,' . $servicioInterno->id,
            'activo' => 'required|boolean',
        ]);
        $servicioInterno->update($request->all());
        return response()->json(['message' => 'Servicio interno actualizado exitosamente', 'data' => $servicioInterno], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicioInterno $servicioInterno, $id)
    {
        $servicioInterno = ServicioInterno::find($id);
        if (!$servicioInterno) {
            return response()->json(['message' => 'Servicio interno no encontrado'], 404);
        }
        $servicioInterno->delete();
        return response()->json(['message' => 'Servicio interno eliminado exitosamente'], 200);
    }

    /**
     * Obtener espacios asociados a un servicio interno
     */
    public function getEspacios($id)
    {
        $servicio = ServicioInterno::with('espacios')->findOrFail($id);
        return response()->json(['data' => $servicio->espacios], 200);
    }

    public function assignEspacio(Request $request, $id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:espacios,id',
        ]);

        $servicio = ServicioInterno::findOrFail($id);
        $servicio->assignEspacio($data['id']);

        return response()->json(['message' => 'Espacio asignado al servicio interno', 'assigned' => $data['id']], 200);
    }

    public function assignEspacios(Request $request, $id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:espacios,id',
        ]);

        $servicio = ServicioInterno::findOrFail($id);
        $servicio->assignEspacios($data['ids']);

        return response()->json(['message' => 'Espacios asignados al servicio interno', 'assigned' => $data['ids']], 200);
    }

    public function removeEspacio(Request $request, $id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:espacios,id',
        ]);

        $servicio = ServicioInterno::findOrFail($id);
        $servicio->removeEspacio($data['id']);

        return response()->json(['message' => 'Espacio removido del servicio interno', 'removed' => $data['id']], 200);
    }

    public function syncEspacios(Request $request, $id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:espacios,id',
        ]);

        $servicio = ServicioInterno::findOrFail($id);
        $servicio->syncEspacios($data['ids']);

        return response()->json(['message' => 'Espacios sincronizados en el servicio interno', 'synced' => $data['ids']], 200);
    }
}
