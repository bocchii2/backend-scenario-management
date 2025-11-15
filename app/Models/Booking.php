<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $table = 'bookings';
    protected $fillable = [
        'usuario_id',
        'espacio_id',
        'tipo_evento_id',
        'nombre_evento',
        'descripcion',
        'proposito',
        'numero_asistentes',
        'fecha_evento_inicio',
        'fecha_evento_fin',
        'duracion_horas',
        'montaje_requerido',
        'fecha_montaje',
        'horas_montaje',
        'evento_privado',
        'grabacion_streaming',
        'moviliarios_solicitados',
        'created_at',
        'updated_at'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function espacio()
    {
        return $this->belongsTo(Espacio::class, 'espacio_id');
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'tipo_evento_id');
    }


    public function servicios()
    {
        return $this->belongsToMany(Servicios::class, 'booking_servicio', 'booking_id', 'servicio_id')
                    ->withTimestamps();
    }


    public function getServiciosByBooking()
    {
        return $this->servicios;
    }

    public function assignServicio($servicio)
    {
        if ($servicio instanceof Servicios) {
            $id = $servicio->id;
        } elseif (is_numeric($servicio)) {
            $id = (int) $servicio;
            Servicios::findOrFail($id);
        } else {
            throw new \InvalidArgumentException('Servicio must be id or Servicios model.');
        }

        return $this->servicios()->syncWithoutDetaching([$id]);
    }

    public function assignServicios(array $servicios)
    {
        $ids = collect($servicios)->map(function ($s) {
            if ($s instanceof Servicios) return $s->id;
            if (is_numeric($s)) {
                Servicios::findOrFail((int) $s);
                return (int) $s;
            }
            throw new \InvalidArgumentException('Each item must be id or Servicios model.');
        })->filter()->unique()->values()->toArray();

        return $this->servicios()->syncWithoutDetaching($ids);
    }

    public function removeServicio($servicio)
    {
        if (is_numeric($servicio)) {
            $servicioModel = Servicios::findOrFail($servicio);
        } elseif ($servicio instanceof Servicios) {
            $servicioModel = $servicio;
        } else {
            throw new \InvalidArgumentException('Servicio must be id or Servicios model.');
        }

        return $this->servicios()->detach($servicioModel->id);
    }

    public function removeServicios(array $servicios)
    {
        $ids = collect($servicios)->map(function ($s) {
            return $s instanceof Servicios ? $s->id : (is_numeric($s) ? (int) $s : null);
        })->filter()->unique()->values()->toArray();

        if (empty($ids)) {
            return 0;
        }

        return $this->servicios()->detach($ids);
    }

    public function syncServicios(array $servicios)
    {
        $ids = collect($servicios)->map(function ($s) {
            return $s instanceof Servicios ? $s->id : (is_numeric($s) ? (int) $s : null);
        })->filter()->unique()->values()->toArray();

        $existing = Servicios::whereIn('id', $ids)->pluck('id')->map(function ($i) { return (int) $i; })->toArray();

        return $this->servicios()->sync($existing);
    }

    public function hasServicio($servicioId)
    {
        return $this->servicios()->where('servicios.id', $servicioId)->exists();
    }

    public function getServicioById($id)
    {
        return $this->servicios()->where('servicios.id', $id)->first();
    }
}
