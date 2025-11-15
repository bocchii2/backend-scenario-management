<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $bookings = Booking::all();
        return response()->json(['message' => 'Lista de reservas', 'data' => $bookings], 200);
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
            'usuario_id' => 'required|integer|exists:users,id',
            'espacio_id' => 'required|integer|exists:espacios,id',
            'tipo_evento_id' => 'required|integer|exists:tipo_eventos,id',
            'nombre_evento' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'proposito' => 'nullable|string',
            'numero_asistentes' => 'required|integer',
            'fecha_evento_inicio' => 'required|date',
            'fecha_evento_fin' => 'required|date|after_or_equal:fecha_evento_inicio',
            'duracion_horas' => 'required|integer',
            'montaje_requerido' => 'required|boolean',
            'fecha_montaje' => 'nullable|date',
            'horas_montaje' => 'nullable|integer',
            'evento_privado' => 'required|boolean',
            'grabacion_streaming' => 'required|boolean',
            'moviliarios_solicitados' => 'nullable|json',
        ]);

        $booking = Booking::create($request->all());

        return response()->json(['message' => 'Reserva creada exitosamente', 'data' => $booking], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking, $bookingId)
    {
        //
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        return response()->json(['message' => 'Detalle de la reserva', 'data' => $booking], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking, $bookingId)
    {
        //
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $request->validate([
            'usuario_id' => 'sometimes|required|integer|exists:users,id',
            'espacio_id' => 'sometimes|required|integer|exists:espacios,id',
            'tipo_evento_id' => 'sometimes|required|integer|exists:tipo_eventos,id',
            'nombre_evento' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'proposito' => 'nullable|string',
            'numero_asistentes' => 'sometimes|required|integer',
            'fecha_evento_inicio' => 'sometimes|required|date',
            'fecha_evento_fin' => 'sometimes|required|date|after_or_equal:fecha_evento_inicio',
            'duracion_horas' => 'sometimes|required|integer',
            'montaje_requerido' => 'sometimes|required|boolean',
            'fecha_montaje' => 'nullable|date',
            'horas_montaje' => 'nullable|integer',
            'evento_privado' => 'sometimes|required|boolean',
            'grabacion_streaming' => 'sometimes|required|boolean',
            'moviliarios_solicitados' => 'nullable|json',
        ]);

        $booking->update($request->all());

        return response()->json(['message' => 'Reserva actualizada exitosamente', 'data' => $booking], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking, $bookingId)
    {
        //
        $booking = Booking::find($bookingId);
        if (!$booking) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }
        $booking->delete();
        return response()->json(['message' => 'Reserva eliminada exitosamente'], 200);
    }



    // metodos relacionados a servicios 
    public function getServiciosByBooking($booking_id)
    {
        $booking = Booking::with('serviciosInternos')->findOrFail($booking_id);
        return response()->json(['data' => $booking->serviciosInternos], 200);
    }


}
