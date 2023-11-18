<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Reserva extends Model
{
    use HasFactory;
    // Relation con Socio
    protected $fillable = [
        'user_id',
        'socio_id',
        'pista_id',
        'socio',
        'pista',
        'deporte',
        'fecha',
        'hora_inicio',
        'hora_fin',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }

    // Relación con Pista
    public function pista(): BelongsTo
    {
        return $this->belongsTo(Pista::class);
    }
}
