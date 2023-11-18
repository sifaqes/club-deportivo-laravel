<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Socio extends Model
{
    use HasFactory;
    // Relación con Reservas
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
