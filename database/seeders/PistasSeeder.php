<?php

namespace Database\Seeders;

use App\Models\Deporte;
use App\Models\Pista;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PistasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Pista::factory(10)->create();
    }
}
