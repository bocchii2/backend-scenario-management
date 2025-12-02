<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServicioInterno extends Model
{
    //
    protected $table = "servicio_internos";

    protected $fillable = ["nombre_servicio","codigo_servicio", "activo", "created_at", "updated_at", "deleted_at"];

    public function espacios()
    {
        return $this->belongsToMany(Espacio::class, "servicio_interno_espacios", "servicio_interno_id", "espacio_id");
    }

    // Métodos auxiliares para gestionar espacios ligados a este servicio interno
    public function assignEspacio($espacio)
    {
        if ($espacio instanceof Espacio) {
            $id = $espacio->id;
        } elseif (is_numeric($espacio)) {
            $id = (int) $espacio;
            Espacio::findOrFail($id);
        } else {
            throw new \InvalidArgumentException('Espacio must be id or Espacio model.');
        }

        return $this->espacios()->syncWithoutDetaching([$id]);
    }

    public function assignEspacios(array $espacios)
    {
        $ids = collect($espacios)->map(function ($e) {
            return $e instanceof Espacio ? $e->id : (is_numeric($e) ? (int) $e : null);
        })->filter()->unique()->values()->toArray();

        $existing = Espacio::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids inexistentes
        return $this->espacios()->syncWithoutDetaching($existing);
    }

    public function removeEspacio($espacio)
    {
        if (is_numeric($espacio)) {
            $espacio = Espacio::findOrFail($espacio);
        } elseif (!($espacio instanceof Espacio)) {
            throw new \InvalidArgumentException('Espacio must be id or Espacio model.');
        }

        return $this->espacios()->detach($espacio->id);
    }

    public function syncEspacios(array $espacios)
    {
        $ids = collect($espacios)->map(function ($e) {
            return $e instanceof Espacio ? $e->id : (is_numeric($e) ? (int) $e : null);
        })->filter()->unique()->values()->toArray();

        $existing = Espacio::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Realizar sync en transacción con los ids existentes
        return DB::transaction(function () use ($existing) {
            return $this->espacios()->sync($existing);
        });
    }

    public function hasEspacio($espacioId)
    {
        return $this->espacios()->where('espacios.id', $espacioId)->exists();
    }

    public function tipoServicioInterno()
    {
        return $this->belongsTo(TiposServiciosInternos::class, 'tipo_servicio_interno_id');
    }



}
