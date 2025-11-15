<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mobiliarios extends Model
{
    //
    protected $table = "mobiliarios";

    protected $fillable = [
        "nombre_mobiliario",
        "descripcion",
        "detalles_tecnicos",
        "activo",
        "categoria_mobiliario_id",
        "created_at",
        "updated_at",
        "deleted_at"
    ];
    protected $casts = [
        'detalles_tecnicos' => 'json',
    ];

    public function categoriaMobiliario()
    {
        return $this->belongsTo(CategoriaMobiliarios::class, 'categoria_mobiliario_id');
    }

    public function espacios()
    {
        return $this->belongsToMany(Espacio::class, 'mobiliarios_espacios', 'mobiliario_id', 'espacio_id')
                    ->withTimestamps();
    }

    // Métodos auxiliares para gestionar espacios ligados a este mobiliario
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
}
