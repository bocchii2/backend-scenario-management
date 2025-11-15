<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Servicios;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingServicioController extends Controller
{
    /**
     * Assign one or more servicios to a booking (adds without detaching existing)
     */
    public function assign(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'required|integer|exists:servicios,id',
        ]);

        $servicios = $data['servicios'];

        $booking->assignServicios($servicios);

        return response()->json([
            'success' => true,
            'message' => 'Servicios asignados al booking.',
            'servicios' => $booking->servicios()->pluck('servicios.id')
        ]);
    }

    /**
     * Remove one or more servicios from a booking
     */
    public function remove(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'required|integer|exists:servicios,id',
        ]);

        $servicios = $data['servicios'];

        $booking->removeServicios($servicios);

        return response()->json([
            'success' => true,
            'message' => 'Servicios removidos del booking.',
            'servicios' => $booking->servicios()->pluck('servicios.id')
        ]);
    }

    /**
     * Sync servicios on a booking (replace current with provided list)
     */
    public function sync(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'servicios' => 'required|array',
            'servicios.*' => 'required|integer|exists:servicios,id',
        ]);

        $servicios = $data['servicios'];

        $booking->syncServicios($servicios);

        return response()->json([
            'success' => true,
            'message' => 'Servicios sincronizados en el booking.',
            'servicios' => $booking->servicios()->pluck('servicios.id')
        ]);
    }
}
