<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            UsersSeeder::class,
            ReservasSeeder::class,
        ]);
    }
}
