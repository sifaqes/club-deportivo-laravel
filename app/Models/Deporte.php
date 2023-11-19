<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @method static where(string $string, mixed $deporte)
 */
class Deporte extends Model
{
    use HasFactory;
    // Relación con Pistas
    public mixed $deporte;

    protected $fillable = [
        'deporte',
    ];


    public function pistas(): HasMany
    {
        return $this->hasMany(Pista::class);
    }
}
