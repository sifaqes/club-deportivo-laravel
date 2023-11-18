<?php

namespace Database\Seeders;

use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use Illuminate\Database\Seeder;

class ReservasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Reserva::class::factory(10)->create();

        $socios = Socio::all();
        $pistas = Pista::all();
        $deportes = Deporte::all();

        foreach ($socios as $socio) {
            $hora_reserva = now()->setHour(9)->setMinute(0)->setSecond(0);
            Reserva::create([
                'socio_id' => $socio->id,
                'pista_id' => $pistas->random()->id,
                'nombre_r' => $socio->nombre,
                'pista_r' => $pistas->random()->pista,
                'deporte_r' => $deportes->random()->deporte,
                'hora_reserva' => $hora_reserva,
            ]);
        }
    }
}
