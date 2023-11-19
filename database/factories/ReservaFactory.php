<?php

namespace Database\Factories;

use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @param $faker
     * @return array<string, mixed>
     */

    public function definition(): array
    {

        $users = User::all();
        $socios = Socio::factory()->create()->all()->random();
        $pistas = Pista::factory()->create()->all()->random();
        $deporte = $pistas->all()->random()->deporte;

        $hora_reserva = now()->setHour(9)->setMinute(0)->setSecond(0);

        $horaInicio = $this->faker->dateTimeBetween('08:00', '22:00', 'Europe/Madrid')->format('H:00');;

        $horaFin = date('H:00', strtotime($horaInicio . ' +1 hour'));

        return [
            'socio_id' => $socios->id,
            'pista_id' => $pistas->id,
            'user_id' => $users->random()->id,
            'socio' => $socios->nombre,
            'pista' => $pistas->pista,
            'deporte' => $deporte,
            'fecha' => $hora_reserva,
            'horaInicio' => $horaInicio,
            'horaFin' => $horaFin,
        ];
    }
}
