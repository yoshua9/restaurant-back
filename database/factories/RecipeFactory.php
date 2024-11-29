<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * El modelo asociado con esta factory.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'sale_price' => $this->faker->randomFloat(2, 5, 50), // Precios entre 5 y 50
        ];
    }
}
