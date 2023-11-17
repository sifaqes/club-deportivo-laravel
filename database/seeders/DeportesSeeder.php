<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('deportes')->insert([
            'deporte' => 'Fútbol',
        ]);

        DB::table('deportes')->insert([
            'deporte' => 'Baloncesto',
        ]);

        DB::table('deportes')->insert([
            'deporte' => 'Tenis',
        ]);

        DB::table('deportes')->insert([
            'deporte' => 'Balonmano',
        ]);

        DB::table('deportes')->insert([
            'deporte' => 'Voleibol',
        ]);

        DB::table('deportes')->insert([
            'deporte' => 'Rugby',
        ]);


    }
}
