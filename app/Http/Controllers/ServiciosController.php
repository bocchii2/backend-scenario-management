<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use App\Models\Booking;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $data = Servicios::all()->load('tipoServicio');
        return response()->json(["data" => $data, "message" => "Servicios obtenidos exitosamente"], 200);
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
        try {
                    //
        $request->validate([
            'tipo_servicio_id' => 'required|exists:tipos_servicios,id',
            'nombre_servicio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
            'datos_adicionales' => 'nullable|array',
        ]);

        $service = Servicios::create($request->all());
        return response()->json(['message'=> 'Servicio creado exitosamente', 'data' => $service],200);
        } catch (\Exception $e) {   
            return response()->json(['message' => 'Error al crear el servicio', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicios $servicios, $servicios_id)
    {
       try {
         $service = Servicios::find($servicios_id);
        if (!$service) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }
        return response()->json(['data' => $service], 200);
       } catch (\Exception $e) {
           return response()->json(['message' => 'Error al obtener el servicio', 'error' => $e->getMessage()], 500);
       }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servicios $servicios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servicios $servicios, $servicios_id)
    {
        //
        try {
            $servicio = Servicios::find($servicios_id);
        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }
        $request->validate([
            'tipo_servicio_id' => 'required|exists:tipos_servicios,id',
            'nombre_servicio' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
            'datos_adicionales' => 'nullable|array',
        ]);
        $service = Servicios::find($servicios_id);
        if (!$service) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }
        $service->update($request->all());
        return response()->json(['message' => 'Servicio actualizado exitosamente', 'data' => $service], 200);
        } catch (\Exception $e) {   
            return response()->json(['message' => 'Error al actualizar el servicio', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicios $servicios, $servicios_id)
    {
        //
        try {
            $service = Servicios::find($servicios_id);
        if (!$service) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }
        $service->delete();
        return response()->json(['message' => 'Servicio eliminado exitosamente'], 200);
        } catch (\Exception $e) {   
            return response()->json(['message' => 'Error al eliminar el servicio', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener bookings asociados a un servicio
     */
    public function getBookings($servicios_id)
    {
        $service = Servicios::with('bookings')->findOrFail($servicios_id);
        return response()->json(['data' => $service->bookings], 200);
    }

    public function assignBooking(Request $request, $servicios_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:bookings,id',
        ]);

        $service = Servicios::findOrFail($servicios_id);
        $service->assignBooking($data['id']);

        return response()->json(['message' => 'Booking asignado al servicio', 'assigned' => $data['id']], 200);
    }

    public function assignBookings(Request $request, $servicios_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:bookings,id',
        ]);

        $service = Servicios::findOrFail($servicios_id);
        $service->assignBookings($data['ids']);

        return response()->json(['message' => 'Bookings asignados al servicio', 'assigned' => $data['ids']], 200);
    }

    public function removeBooking(Request $request, $servicios_id)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:bookings,id',
        ]);

        $service = Servicios::findOrFail($servicios_id);
        $service->removeBooking($data['id']);

        return response()->json(['message' => 'Booking removido del servicio', 'removed' => $data['id']], 200);
    }

    public function syncBookings(Request $request, $servicios_id)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:bookings,id',
        ]);

        $service = Servicios::findOrFail($servicios_id);
        $service->syncBookings($data['ids']);

        return response()->json(['message' => 'Bookings sincronizados en el servicio', 'synced' => $data['ids']], 200);
    }
}
