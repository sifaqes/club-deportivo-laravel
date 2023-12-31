<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @method static where(string $string, int|string|null $socioId)
 */
class Reserva extends Model
{
    use HasFactory;
    // Relation con Socio
    protected $fillable = [
        'userId',
        'socio_id',
        'pista_id',
        'socio',
        'pista',
        'deporte',
        'fecha',
        'horaInicio',
        'horaFin',
    ];

    // Relación con el modelo User
    public function user(): BelongsTo
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
