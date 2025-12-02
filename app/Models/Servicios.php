<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    //
    protected $table = "servicios";
    protected $fillable = ["tipo_servicio_id", "nombre_servicio", "descripcion","estado","datos_adicionales","created_at","updated_at"];

    protected $casts = [
        'datos_adicionales' => 'array',
    ];

    protected $dates = ['created_at','updated_at'];

    public function tipoServicio()
    {
        return $this->belongsTo(TiposServicios::class, 'tipo_servicio_id');
    }


    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_servicio', 'servicio_id', 'booking_id')
                    ->withTimestamps();
    }

    // Métodos auxiliares para gestionar bookings ligados a este servicio
    public function assignBooking($booking)
    {
        if ($booking instanceof Booking) {
            $id = $booking->id;
        } elseif (is_numeric($booking)) {
            $id = (int) $booking;
            Booking::findOrFail($id);
        } else {
            throw new \InvalidArgumentException('Booking must be id or Booking model.');
        }

        return $this->bookings()->syncWithoutDetaching([$id]);
    }

    public function assignBookings(array $bookings)
    {
        $ids = collect($bookings)->map(function ($b) {
            if ($b instanceof Booking) return $b->id;
            if (is_numeric($b)) {
                Booking::findOrFail((int) $b);
                return (int) $b;
            }
            throw new \InvalidArgumentException('Each item must be id or Booking model.');
        })->toArray();

        return $this->bookings()->syncWithoutDetaching($ids);
    }

    public function removeBooking($booking)
    {
        if (is_numeric($booking)) {
            $booking = Booking::findOrFail($booking);
        } elseif (!($booking instanceof Booking)) {
            throw new \InvalidArgumentException('Booking must be id or Booking model.');
        }

        return $this->bookings()->detach($booking->id);
    }

    public function syncBookings(array $bookings)
    {
        $ids = collect($bookings)->map(function ($b) {
            return $b instanceof Booking ? $b->id : (is_numeric($b) ? (int) $b : null);
        })->filter()->toArray();

        return $this->bookings()->sync($ids);
    }

    public function hasBooking($bookingId)
    {
        return $this->bookings()->where('bookings.id', $bookingId)->exists();
    }

    public function categoriaServicio()
    {
        return $this->belongsTo(CategoriaServicios::class, 'categoria_servicio_id');
    }
}