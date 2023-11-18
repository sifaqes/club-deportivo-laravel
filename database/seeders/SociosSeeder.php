<?php

namespace Database\Seeders;
use App\Models\Socio;
use Illuminate\Database\Seeder;


class SociosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Socio::factory(10)->create();
    }
}
