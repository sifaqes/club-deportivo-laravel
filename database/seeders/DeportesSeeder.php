<?php

namespace Database\Seeders;

use App\Models\Deporte;
use Illuminate\Database\Seeder;


class DeportesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Deporte::factory(10)->create();
    }
}
