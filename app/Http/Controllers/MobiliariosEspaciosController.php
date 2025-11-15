<?php

namespace App\Http\Controllers;

use App\Models\MobiliariosEspacios;
use App\Models\Espacio;
use App\Models\Mobiliarios;
use Illuminate\Http\Request;

class MobiliariosEspaciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MobiliariosEspacios::with(['mobiliario', 'espacio'])->paginate(15);
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
            'mobiliario_id' => 'required|exists:mobiliarios,id',
            'espacio_id' => 'required|exists:espacios,id',
        ]);

        // Verificar que no exista ya la asociación
        $existe = MobiliariosEspacios::where('mobiliario_id', $data['mobiliario_id'])
            ->where('espacio_id', $data['espacio_id'])
            ->exists();

        if ($existe) {
            return response()->json(['message' => 'Esta asociación ya existe'], 409);
        }

        $asociacion = MobiliariosEspacios::create($data);

        return response()->json($asociacion->load(['mobiliario', 'espacio']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($asociacion_id)
    {
        $asociacion = MobiliariosEspacios::with(['mobiliario', 'espacio'])
            ->findOrFail($asociacion_id);
        
        return response()->json($asociacion);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MobiliariosEspacios $mobiliariosEspacios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $asociacion_id)
    {
        $asociacion = MobiliariosEspacios::findOrFail($asociacion_id);
        
        $data = $request->validate([
            'mobiliario_id' => 'sometimes|required|exists:mobiliarios,id',
            'espacio_id' => 'sometimes|required|exists:espacios,id',
        ]);

        $asociacion->update($data);

        return response()->json([
            'message' => 'Asociación actualizada correctamente',
            'data' => $asociacion->load(['mobiliario', 'espacio'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($asociacion_id)
    {
        $asociacion = MobiliariosEspacios::findOrFail($asociacion_id);
        $asociacion->delete();
        
        return response()->json(['message' => 'Asociación eliminada correctamente'], 204);
    }

    /**
     * Obtener mobiliarios de un espacio específico
     */
    public function getMobiliariosByEspacio($espacio_id)
    {
        $espacio = Espacio::findOrFail($espacio_id);
        return response()->json($espacio->mobiliarios()->with('categoriaMobiliario')->paginate(15));
    }

    /**
     * Obtener espacios de un mobiliario específico
     */
    public function getEspaciosByMobiliario($mobiliario_id)
    {
        $mobiliario = Mobiliarios::findOrFail($mobiliario_id);
        return response()->json($mobiliario->espacios()->paginate(15));
    }

    /**
     * Desasociar múltiples mobiliarios de un espacio
     * DELETE /espacios/{espacio_id}/mobiliarios/desasociar
     * Body: { "mobiliario_ids": [1, 2, 3] }
     */
    public function desasociarMultiples($espacio_id, Request $request)
    {
        // Verificar que el espacio existe
        $espacio = Espacio::findOrFail($espacio_id);

        $data = $request->validate([
            'mobiliario_ids' => 'required|array|min:1',
            'mobiliario_ids.*' => 'exists:mobiliarios,id',
        ]);

        $mobiliario_ids = $data['mobiliario_ids'];
        
        $eliminadas = MobiliariosEspacios::where('espacio_id', $espacio_id)
            ->whereIn('mobiliario_id', $mobiliario_ids)
            ->delete();

        return response()->json([
            'message' => 'Mobiliarios desasociados del espacio correctamente',
            'espacio_id' => $espacio_id,
            'eliminadas' => $eliminadas,
        ]);
    }

    /**
     * Desasociar un mobiliario de un espacio
     */
    public function desasociar(Request $request)
    {
        $data = $request->validate([
            'mobiliario_id' => 'required|exists:mobiliarios,id',
            'espacio_id' => 'required|exists:espacios,id',
        ]);

        $deleted = MobiliariosEspacios::where('mobiliario_id', $data['mobiliario_id'])
            ->where('espacio_id', $data['espacio_id'])
            ->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Asociación no encontrada'], 404);
        }

        return response()->json(['message' => 'Mobiliario desasociado del espacio correctamente']);
    }

    /**
     * Asociar múltiples mobiliarios a un espacio
     * POST /espacios/{espacio_id}/mobiliarios/asociar
     * Body: { "mobiliario_ids": [1, 2, 3] }
     */
    public function asociarMultiples($espacio_id, Request $request)
    {
        // Verificar que el espacio existe
        $espacio = Espacio::findOrFail($espacio_id);

        $data = $request->validate([
            'mobiliario_ids' => 'required|array|min:1',
            'mobiliario_ids.*' => 'exists:mobiliarios,id',
        ]);

        $mobiliario_ids = $data['mobiliario_ids'];
        $creadas = 0;
        $existentes = 0;
        $asociaciones = [];

        foreach ($mobiliario_ids as $mobiliario_id) {
            // Verificar que el mobiliario existe
            $mobiliario = Mobiliarios::findOrFail($mobiliario_id);

            // Verificar si ya existe la asociación
            $existe = MobiliariosEspacios::where('mobiliario_id', $mobiliario_id)
                ->where('espacio_id', $espacio_id)
                ->exists();

            if (!$existe) {
                $asociacion = MobiliariosEspacios::create([
                    'mobiliario_id' => $mobiliario_id,
                    'espacio_id' => $espacio_id,
                ]);
                $asociaciones[] = $asociacion->load(['mobiliario', 'espacio']);
                $creadas++;
            } else {
                $existentes++;
            }
        }

        return response()->json([
            'message' => 'Mobiliarios asociados al espacio correctamente',
            'espacio_id' => $espacio_id,
            'creadas' => $creadas,
            'existentes' => $existentes,
            'total_procesadas' => count($mobiliario_ids),
            'asociaciones_creadas' => $asociaciones,
        ], 201);
    }
}
