<?php

namespace App\Http\Controllers;

use App\Models\EventosInternos;
use Illuminate\Http\Request;

class EventosInternosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = EventosInternos::all();
        return response()->json(['message' => 'Eventos internos obtenidos correctamente', 'data' => $events], 200);
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
            'tipo_evento_interno_id' => 'required|exists:tipos_eventos_internos,id',
            'nombre_evento' => 'required|string|max:255',
            'evento_privado' => 'required|boolean',
            'descripcion' => 'nullable|string',
            'fecha_evento' => 'required|date',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);
        $evento = EventosInternos::create($request->all());
        return response()->json(['message' => 'Evento interno creado correctamente', 'data' => $evento],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(EventosInternos $eventosInternos, $evento_id)
    {
        //
        $evento = EventosInternos::find($evento_id);
        if (!$evento) {
            return response()->json(['message' => 'Evento interno no encontrado'], 404);
        }
        return response()->json(['message' => 'Evento interno obtenido correctamente', 'data' => $evento], 200);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventosInternos $eventosInternos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventosInternos $eventosInternos, $evento_id)
    {
        //
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'tipo_evento_interno_id' => 'required|exists:tipos_eventos_internos,id',
            'nombre_evento' => 'required|string|max:255',
            'evento_privado' => 'required|boolean',
            'descripcion' => 'nullable|string',
            'fecha_evento' => 'required|date',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);
        $evento = EventosInternos::find($evento_id);
        if (!$evento) {
            return response()->json(['message' => 'Evento interno no encontrado'], 404);
        }
        $evento->update($request->all());
        return response()->json(['message' => 'Evento interno actualizado correctamente', 'data' => $evento], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventosInternos $eventosInternos, $evento_id)
    {
        //
        $evento = EventosInternos::find($evento_id);
        if (!$evento) {
            return response()->json(['message' => 'Evento interno no encontrado'], 404);
        }
        $evento->delete();
        return response()->json(['message' => 'Evento interno eliminado correctamente'], 200);
    }
}
