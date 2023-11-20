<?php

namespace Database\Factories;

use App\Models\Deporte;
use App\Models\Pista;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pista>
 */
class PistaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Pista::class;

    public function definition(): array
    {
        return [
            'deporte_id' => Deporte::factory(),
            'pista' => $this->faker->randomElement(['pista A', 'pista B', 'pista C','pista D']),
            'disponibilidad' => true,
        ];
    }
}
