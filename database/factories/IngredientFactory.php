<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    /**
     * El modelo asociado con esta factory.
     *
     * @var string
     */
    protected $model = Ingredient::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'cost' => $this->faker->randomFloat(2, 0.5, 10), // Costes entre 0.5 y 10
        ];
    }
}
