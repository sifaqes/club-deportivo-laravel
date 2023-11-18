<?php

namespace Database\Factories;

use App\Models\Deporte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deporte>
 */
class DeporteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Deporte::class;

    public function definition(): array
    {
        return [
            'deporte' => $this->faker->randomElement(['gymnastic','football',]),
        ];
    }
}
