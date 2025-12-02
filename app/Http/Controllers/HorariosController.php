<?php

namespace App\Http\Controllers;

use App\Models\Horarios;
use Illuminate\Http\Request;

use App\Models\Espacio;

class HorariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $horarios = Horarios::with('espacios')->get();

        return response()->json(['message' => 'Lista de horarios', 'data' => $horarios], 200);
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
            'nombre_horario' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'activo' => 'nullable|boolean',
        ]);
        $horario = Horarios::create($request->all());
        return response()->json(['message'=> 'Horario creado con exito', 'data' => $horario],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Horarios $horarios, $id_horario)
    {
        //
        $horario = Horarios::with('espacios', 'tarifas')->findOrFail( $id_horario );
        return response()->json(['message'=> 'horario encontrado', 'data' => $horario],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horarios $horarios, $id_horario)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horarios $horarios, $id_horario)
    {
        //        
        $horario = Horarios::findOrFail( $id_horario );
        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }
        $request->validate([
            'nombre_horario' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'sometimes|required|date',
            'fecha_fin' => 'sometimes|required|date|after_or_equal:fecha_inicio',
            'hora_inicio' => 'sometimes|required|date_format:H:i:s',
            'hora_fin' => 'sometimes|required|date_format:H:i:s|after:hora_inicio',
            'activo' => 'nullable|boolean',
        ]);
        $horario->update($request->all());
        return response()->json(['message' => 'Horario actualizado con exito', 'data' => $horario], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horarios $horarios, $id_horario)
    {
        //
        $horario = Horarios::findOrFail( $id_horario );
        if (!$horario) {
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }
        $horario->delete();
        return response()->json(['message' => 'Horario eliminado con exito'], 200);
    }

    /**
     * Obtener los espacios asociados a un horario
     */
    public function getEspacios($id_horario)
    {
        $horario = Horarios::with('espacios')->findOrFail($id_horario);
        return response()->json(['data' => $horario->espacios], 200);
    }

    public function assignEspacio(Request $request, $id_horario)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:espacios,id',
        ]);

        $horario = Horarios::findOrFail($id_horario);
        $horario->assignEspacio($data['id']);

        return response()->json(['message' => 'Espacio asignado al horario', 'assigned' => $data['id']], 200);
    }

    public function assignEspacios(Request $request, $id_horario)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:espacios,id',
        ]);

        $horario = Horarios::findOrFail($id_horario);
        $horario->assignEspacios($data['ids']);

        return response()->json(['message' => 'Espacios asignados al horario', 'assigned' => $data['ids']], 200);
    }

    public function removeEspacio(Request $request, $id_horario)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:espacios,id',
        ]);

        $horario = Horarios::findOrFail($id_horario);
        $horario->removeEspacio($data['id']);

        return response()->json(['message' => 'Espacio removido del horario', 'removed' => $data['id']], 200);
    }

    public function syncEspacios(Request $request, $id_horario)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:espacios,id',
        ]);

        $horario = Horarios::findOrFail($id_horario);
        $horario->syncEspacios($data['ids']);

        return response()->json(['message' => 'Espacios sincronizados en el horario', 'synced' => $data['ids']], 200);
    }
}
