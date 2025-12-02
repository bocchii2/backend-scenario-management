<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;
use Illuminate\Support\Facades\DB;

class Espacio extends Model
{
    use SoftDeletes, Auditable;

    protected $table = 'espacios';

    protected $fillable = [
        'nombre_espacio',
        'capacidad',
        'descripcion',
        'metro_cuadrado',
        'pies_cuadrados',
        'altura',
        'otros_atributos',
        'atributos_capacidad',
        'categoria_espacio_id',
        'departamento_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'otros_atributos' => 'array',
        'atributos_capacidad' => 'array',
        'capacidad' => 'integer',
        'metro_cuadrado' => 'integer',
        'pies_cuadrados' => 'integer',
        'altura' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relaciones
    public function categoria()
    {
        return $this->belongsTo(CategoriaEspacio::class, 'categoria_espacio_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    // Auditoría: usuario que creó / actualizó
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function actualizador()
    {
        return $this->belongsTo(Usuario::class, 'updated_by');
    }

    // Scopes útiles
    public function scopeByDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    public function scopeConCapacidadMinima($query, int $min)
    {
        return $query->where('capacidad', '>=', $min);
    }

    // Accessor / Mutator de ejemplo
    public function getNombreEspacioAttribute($value)
    {
        return ucfirst($value);
    }

    public function setNombreEspacioAttribute($value)
    {
        $this->attributes['nombre_espacio'] = trim($value);
    }

    // Métodos de negocio
    public function isAvailable(\DateTimeInterface $from = null, \DateTimeInterface $to = null): bool
    {
        // Implementa según tus reglas / reservas; aquí un placeholder
        // return !$this->reservas()->between($from, $to)->exists();
        return true;
    }

    public function capacidadDescripcion(): string
    {
        return $this->capacidad ? (string) $this->capacidad : 'No especificada';
    }


    public function serviciosInternos()
    {
        return $this->belongsToMany(ServicioInterno::class, 'servicio_interno_espacios', 'espacio_id', 'servicio_interno_id');
    }


    public function getServicioInternoById($id)
    {
        return $this->serviciosInternos()->where('servicio_internos.id', $id)->first();
    }
    public function assignServicioInterno($servicioInterno)
    {
        if ($servicioInterno instanceof ServicioInterno) {
            $id = $servicioInterno->id;
        } elseif (is_numeric($servicioInterno)) {
            $id = (int) $servicioInterno;
            ServicioInterno::findOrFail($id);
        } else {
            throw new \InvalidArgumentException('ServicioInterno must be id or ServicioInterno model.');
        }

        return $this->serviciosInternos()->syncWithoutDetaching([$id]);
    }

    public function assignServiciosInternos(array $serviciosInternos)
    {
        $ids = collect($serviciosInternos)->map(function ($s) {
            return $s instanceof ServicioInterno ? $s->id : (is_numeric($s) ? (int) $s : null);
        })->filter()->unique()->values()->toArray();

        // Validación en bloque: reduce queries
        $existing = ServicioInterno::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids que no existen (tolerancia a fallos)
        return $this->serviciosInternos()->syncWithoutDetaching($existing);
    }

    public function removeServiciosInternos(array $serviciosInternos)
    {
        $ids = collect($serviciosInternos)->map(function ($s) {
            return $s instanceof ServicioInterno ? $s->id : (is_numeric($s) ? (int) $s : null);
        })->filter()->unique()->values()->toArray();

        if (empty($ids)) {
            return 0;
        }

        // Detach sólo los existentes (no falla si alguno no existe)
        $existing = ServicioInterno::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        return $this->serviciosInternos()->detach($existing);
    }

    public function syncServiciosInternos(array $serviciosInternos)
    {
        $ids = collect($serviciosInternos)->map(function ($s) {
            return $s instanceof ServicioInterno ? $s->id : (is_numeric($s) ? (int) $s : null);
        })->filter()->unique()->values()->toArray();

        // Validación en bloque: reduce queries
        $existing = ServicioInterno::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids inexistentes; realizar sync con los existentes en transacción
        return DB::transaction(function () use ($existing) {
            return $this->serviciosInternos()->sync($existing);
        });
    }


    /**
     * MOBILARIOS RELATIONSHIP
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Mobiliarios, Espacio, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function mobiliarios()
    {
        return $this->belongsToMany(Mobiliarios::class, 'mobiliarios_espacios', 'espacio_id', 'mobiliario_id')
                    ->withTimestamps();
    }

    public function getMobiliarioById($id)
    {
        return $this->mobiliarios()->where('mobiliarios.id', $id)->first();
    }

    public function assignMobiliario($mobiliario)
    {
        if ($mobiliario instanceof Mobiliarios) {
            $id = $mobiliario->id;
        } elseif (is_numeric($mobiliario)) {
            $id = (int) $mobiliario;
            Mobiliarios::findOrFail($id);
        } else {
            throw new \InvalidArgumentException('Mobiliario must be id or Mobiliarios model.');
        }

        return $this->mobiliarios()->syncWithoutDetaching([$id]);
    }

    public function assignMobiliarios(array $mobiliarios)
    {
        $ids = collect($mobiliarios)->map(function ($m) {
            return $m instanceof Mobiliarios ? $m->id : (is_numeric($m) ? (int) $m : null);
        })->filter()->unique()->values()->toArray();

        $existing = Mobiliarios::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids inexistentes y asociar sólo los que existen
        return $this->mobiliarios()->syncWithoutDetaching($existing);
    }

    public function removeMobiliario($mobiliario)
    {
        if (is_numeric($mobiliario)) {
            $mobiliario = Mobiliarios::findOrFail($mobiliario);
        } elseif (!($mobiliario instanceof Mobiliarios)) {
            throw new \InvalidArgumentException('Mobiliario must be id or Mobiliarios model.');
        }

        return $this->mobiliarios()->detach($mobiliario->id);
    }

    public function syncMobiliarios(array $mobiliarios)
    {
        $ids = collect($mobiliarios)->map(function ($m) {
            return $m instanceof Mobiliarios ? $m->id : (is_numeric($m) ? (int) $m : null);
        })->filter()->unique()->values()->toArray();

        $existing = Mobiliarios::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids inexistentes; realizar sync con los existentes en transacción
        return DB::transaction(function () use ($existing) {
            return $this->mobiliarios()->sync($existing);
        });
    }




    /**
     * HORARIOS RELATIONSHIP
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Horarios, Espacio, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function horarios()
    {
        return $this->belongsToMany(Horarios::class, 'horarios_espacios', 'espacio_id', 'horario_id');
    }

    public function getHorarioById($id)
    {
        return $this->horarios()->where('horarios.id', $id)->first();
    }

    // Métodos auxiliares para gestionar horarios ligados a este espacio
    public function assignHorario($horario)
    {
        if ($horario instanceof Horarios) {
            $id = $horario->id;
        } elseif (is_numeric($horario)) {
            $id = (int) $horario;
            Horarios::findOrFail($id);
        } else {
            throw new \InvalidArgumentException('Horario must be id or Horarios model.');
        }

        return $this->horarios()->syncWithoutDetaching([$id]);
    }

    public function assignHorarios(array $horarios)
    {
        $ids = collect($horarios)->map(function ($h) {
            return $h instanceof Horarios ? $h->id : (is_numeric($h) ? (int) $h : null);
        })->filter()->unique()->values()->toArray();

        $existing = Horarios::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids inexistentes y asociar sólo los que existen
        return $this->horarios()->syncWithoutDetaching($existing);
    }

    public function removeHorario($horario)
    {
        if (is_numeric($horario)) {
            $horario = Horarios::findOrFail($horario);
        } elseif (!($horario instanceof Horarios)) {
            throw new \InvalidArgumentException('Horario must be id or Horarios model.');
        }

        return $this->horarios()->detach($horario->id);
    }

    public function syncHorarios(array $horarios)
    {
        $ids = collect($horarios)->map(function ($h) {
            return $h instanceof Horarios ? $h->id : (is_numeric($h) ? (int) $h : null);
        })->filter()->unique()->values()->toArray();

        $existing = Horarios::whereIn('id', $ids)->pluck('id')->map(function ($i) {
            return (int) $i;
        })->toArray();

        // Ignorar ids inexistentes; realizar sync con los existentes en transacción
        return DB::transaction(function () use ($existing) {
            return $this->horarios()->sync($existing);
        });
    }

    public function hasHorario($horarioId)
    {
        return $this->horarios()->where('horarios.id', $horarioId)->exists();
    }

    public function horario()
    {
        return $this->belongsToMany(Horarios::class, 'horarios_espacios', 'espacio_id', 'horario_id');
    }


}