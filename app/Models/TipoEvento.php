<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEvento extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_eventos';
    protected $fillable = [
        'nombre_tipo_evento',
        'descripcion',
        'estado',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public  function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'tipo_evento_id');
    }
}
