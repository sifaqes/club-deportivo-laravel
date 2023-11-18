<?php

namespace Database\Factories;

use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $socios = Socio::factory()->create()->all();
        $pistas = Pista::factory()->create()->all();

        $hora_reserva = now()->setHour(9)->setMinute(0)->setSecond(0);

        return [

            'socio_id' => $socios->random()->id,
            'pista_id' => $pistas->random()->id,
            'socio' => $socios->random()->nombre,
            'pista' => $pistas->random()->pista,
            'deporte' => $pistas->random()->deporte->deporte,
            'fecha' => $this->faker->dateTimeBetween('now', '+1 years'),
            'hora' => $hora_reserva,

        ];
    }
}
