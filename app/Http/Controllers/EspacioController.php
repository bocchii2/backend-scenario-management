<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use Illuminate\Http\Request;
use App\Models\Horarios;

class EspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Espacio::with(['categoria', 'horario','departamento','serviciosInternos', 'creador', 'actualizador'])->paginate(15);
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
            'nombre_espacio' => 'required|string|max:255',
            'capacidad' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'metro_cuadrado' => 'nullable|integer',
            'pies_cuadrados' => 'nullable|integer',
            'altura' => 'nullable|integer',
            'otros_atributos' => 'nullable|array',
            'atributos_capacidad' => 'nullable|array',
            'categoria_espacio_id' => 'required|exists:categoria_espacios,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'servicios_internos' => 'nullable|array',
            'servicios_internos.*' => 'integer|exists:servicio_internos,id',
            'horarios' => 'nullable|array',
            'horarios.*' => 'integer|exists:horarios,id',
            
        ]);

        // Agregar información de auditoría
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $espacio = Espacio::create($data);

        if ($espacio) {
            // Asignar servicios internos si se proporcionan
            if (isset($data['servicios_internos'])) {
                $espacio->assignServiciosInternos($data['servicios_internos']);
            }
        }

        if ($espacio) {
            // Asignar horarios si se proporcionan
            if (isset($data['horarios'])) {
                $espacio->assignHorarios($data['horarios']);
            }
        }



        return response()->json($espacio->load(['categoria', 'departamento', 'creador', 'actualizador']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Espacio $espacio, $id)
    {
        $espacio = Espacio::find($id);
        if (!$espacio) {
            return response()->json(["message" => "Espacio no encontrado"], 404);
        }
        return $espacio->load(['categoria', 'departamento', 'creador', 'actualizador', 'serviciosInternos']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Espacio $espacio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $espacio = Espacio::findOrFail($id);
        $data = $request->validate([
            'nombre_espacio' => 'sometimes|required|string|max:255',
            'capacidad' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'metro_cuadrado' => 'nullable|integer',
            'pies_cuadrados' => 'nullable|integer',
            'altura' => 'nullable|integer',
            'otros_atributos' => 'nullable|array',
            'atributos_capacidad' => 'nullable|array',
            'categoria_espacio_id' => 'sometimes|required|exists:categoria_espacios,id',
            'departamento_id' => 'sometimes|required|exists:departamentos,id',
        ]);

        // Agregar información de auditoría
        $data['updated_by'] = auth()->id();

        $espacio->update($data);

        return response()->json(["message" => "Espacio actualizado correctamente", "data" => $espacio->load(['categoria', 'departamento', 'creador', 'actualizador'])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $espacio = Espacio::findOrFail($id);
        $espacio->delete();
        return response()->json(["message" => "Espacio eliminado correctamente"], 204);
    }

    /**
     * Get spaces by department
     */
    public function getEspaciosByDepartamento($departamento_id)
    {
        return Espacio::where('departamento_id', $departamento_id)
            ->with(['categoria', 'departamento', 'creador', 'actualizador'])
            ->paginate(15);
    }

    /**
     * Get spaces by category
     */
    public function getEspaciosByCategoria($categoria_id)
    {
        return Espacio::where('categoria_espacio_id', $categoria_id)
            ->with(['categoria', 'departamento', 'creador', 'actualizador'])
            ->paginate(15);
    }

    /**
     * Get spaces with minimum capacity
     */
    public function getEspaciosConCapacidadMinima($capacidad)
    {
        return Espacio::conCapacidadMinima($capacidad)
            ->with(['categoria', 'departamento', 'creador', 'actualizador'])
            ->paginate(15);
    }

    /**
     * Obtener horarios asociados a un espacio
     */
    public function getHorarios($espacio_id)
    {
        $espacio = Espacio::with('horarios')->findOrFail($espacio_id);
        return response()->json(['data' => $espacio->horarios], 200);
    }

    public function assignHorario(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:horarios,id',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->assignHorario($data['id']);

        return response()->json(['message' => 'Horario asignado al espacio', 'assigned' => $data['id']], 200);
    }

    public function assignHorarios(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->assignHorarios($data['ids']);

        return response()->json(['message' => 'Horarios asignados al espacio', 'assigned' => $data['ids']], 200);
    }

    public function removeHorario(Request $request, $espacio_id, $horario_id)
    {
        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->removeHorario($horario_id);

        return response()->json(['message' => 'Horario removido del espacio', 'removed' => $horario_id], 200);
    }

    // Sincronizar horarios asociados a un espacio
    public function syncHorarios(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->syncHorarios($data['ids']);

        return response()->json(['message' => 'Horarios sincronizados en el espacio', 'synced' => $data['ids']], 200);
    }




    // metodos para servicios internos desde espacios
    public function assignServicioInterno(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:servicio_internos,id',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->assignServicioInterno($data['id']);

        return response()->json(['message' => 'Servicio interno asignado al espacio', 'assigned' => $data['id']], 200);
    }

    public function assignServiciosInternos(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->assignServiciosInternos($data['ids']);

        return response()->json(['message' => 'Servicios internos asignados al espacio', 'assigned' => $data['ids']], 200);
    }


    public function removeServicioInterno(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:servicio_internos,id',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        // El modelo dispone de removeServiciosInternos (plural); delegamos con arreglo de un elemento
        $espacio->removeServiciosInternos([$data['id']]);

        return response()->json(['message' => 'Servicio interno removido del espacio', 'removed' => $data['id']], 200);
    }

    public function removeServiciosInternos(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->removeServiciosInternos($data['ids']);

        return response()->json(['message' => 'Servicios internos removidos del espacio', 'removed' => $data['ids']], 200);
    }
    // metodo para sincronizar servicios internos
    public function syncServiciosInternos(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->syncServiciosInternos($data['ids']);

        return response()->json(['message' => 'Servicios internos sincronizados en el espacio', 'synced' => $data['ids']], 200);
    }


    /**
     * MOBILIARIOS RELATED METHODS
     */
    public function getMobiliariosByEspacio($espacio_id)
    {
        $espacio = Espacio::with('mobiliarios')->findOrFail($espacio_id);
        return response()->json(['data' => $espacio->mobiliarios], 200);
    }


    public function assignMobiliario(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:mobiliarios,id',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->assignMobiliario($data['id']);

        return response()->json(['message' => 'Mobiliario asignado al espacio', 'assigned' => $data['id']], 200);
    }

    public function assignMobiliarios(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->assignMobiliarios($data['ids']);

        return response()->json(['message' => 'Mobiliarios asignados al espacio', 'assigned' => $data['ids']], 200);
    }


    public function removeMobiliario(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:mobiliarios,id',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->removeMobiliario($data['id']);

        return response()->json(['message' => 'Mobiliario removido del espacio', 'removed' => $data['id']], 200);
    }

    public function syncMobiliarios(Request $request, $espacio_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $espacio = Espacio::findOrFail($espacio_id);
        $espacio->syncMobiliarios($data['ids']);

        return response()->json(['message' => 'Mobiliarios sincronizados en el espacio', 'synced' => $data['ids']], 200);
    }
}