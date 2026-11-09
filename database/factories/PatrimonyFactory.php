<?php

namespace Database\Factories;

use App\Models\Environment;
use App\Models\Patrimony;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patrimony>
 */
class PatrimonyFactory extends Factory
{
    protected $model = Patrimony::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numberBetween(1000, 9999),
            'description' => $this->faker->sentence(),
            'environment_id' => Environment::factory(), // Cria um ambiente associado por padrão se nenhum for passado
            'state_id' => State::factory(), // Ou passe um ID fixo válido caso os estados já existam no banco, ex: 1
        ];
    }
}
