<?php

namespace App\Http\Controllers;

use App\Models\Mobiliarios;
use Illuminate\Http\Request;
use App\Models\Espacio;

class MobiliariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Mobiliarios::with(['categoriaMobiliario', 'espacios'])
            ->paginate(15);
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
            'nombre_mobiliario' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'detalles_tecnicos' => 'nullable|array',
            'activo' => 'nullable|boolean',
            'categoria_mobiliario_id' => 'required|exists:categoria_mobiliarios,id',
        ]);

        $data['activo'] = $data['activo'] ?? true;
        $mobiliario = Mobiliarios::create($data);

        return response()->json($mobiliario->load(['categoriaMobiliario']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($mobiliario_id)
    {
        $mobiliario = Mobiliarios::with(['categoriaMobiliario', 'espacios'])
            ->findOrFail($mobiliario_id);
        
        return response()->json($mobiliario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mobiliarios $mobiliarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $mobiliario_id)
    {
        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        
        $data = $request->validate([
            'nombre_mobiliario' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'detalles_tecnicos' => 'nullable|json',
            'activo' => 'nullable|boolean',
            'categoria_mobiliario_id' => 'sometimes|required|exists:categoria_mobiliarios,id',
        ]);

        $mobiliario->update($data);

        return response()->json([
            'message' => 'Mobiliario actualizado correctamente',
            'data' => $mobiliario->load(['categoriaMobiliario'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mobiliario_id)
    {
        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        $mobiliario->delete();
        
        return response()->json(['message' => 'Mobiliario eliminado correctamente'], 204);
    }

    /**
     * Get mobiliarios by category
     */
    public function getMobiliariosByCategoria($categoria_id)
    {
        return Mobiliarios::where('categoria_mobiliario_id', $categoria_id)
            ->with(['categoriaMobiliario', 'espacios'])
            ->paginate(15);
    }

    /**
     * Get espacios associated with a mobiliario
     */
    public function getEspacios($mobiliario_id)
    {
        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        return response()->json($mobiliario->espacios()->paginate(15));
    }

    public function assignEspacio(Request $request, $mobiliario_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:espacios,id',
        ]);

        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        $mobiliario->assignEspacio($data['id']);

        return response()->json(['message' => 'Espacio asignado al mobiliario', 'assigned' => $data['id']], 200);
    }

    public function assignEspacios(Request $request, $mobiliario_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:espacios,id',
        ]);

        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        $mobiliario->assignEspacios($data['ids']);

        return response()->json(['message' => 'Espacios asignados al mobiliario', 'assigned' => $data['ids']], 200);
    }

    public function removeEspacio(Request $request, $mobiliario_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:espacios,id',
        ]);

        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        $mobiliario->removeEspacio($data['id']);

        return response()->json(['message' => 'Espacio removido del mobiliario', 'removed' => $data['id']], 200);
    }

    public function syncEspacios(Request $request, $mobiliario_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:espacios,id',
        ]);

        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        $mobiliario->syncEspacios($data['ids']);

        return response()->json(['message' => 'Espacios sincronizados en el mobiliario', 'synced' => $data['ids']], 200);
    }
}
