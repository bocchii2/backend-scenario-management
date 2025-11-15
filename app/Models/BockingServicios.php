<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BockingServicios extends Model
{
    //
    protected $table = "booking_servicios";
    protected $fillable = [
        "booking_id",
        "servicio_id",
        "cantidad",
        "created_at",
        "updated_at"
    ];

    public $timestamps = false;

    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }

    public function bocking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function attachServiciosToBooking($bookingId, $servicios)
    {
        foreach ($servicios as $servicio) {
            self::create([
                'booking_id' => $bookingId,
                'servicio_id' => $servicio['servicio_id'],
                'cantidad' => $servicio['cantidad'],
            ]);
        }
    }
}
