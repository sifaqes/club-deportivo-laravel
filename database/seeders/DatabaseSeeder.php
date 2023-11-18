<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Deporte;
use App\Models\Pista;
use App\Models\Socio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        Socio::factory(5)->create();
//        Pista::factory(5)->create();
//        Deporte::factory(5)->create();

        $this->call([
            UsersSeeder::class,
            ReservasSeeder::class,
        ]);
    }
}
