<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para verificar la creación de un ingrediente.
     */
    public function test_it_can_create_an_ingredient()
    {
        $ingredient = Ingredient::factory()->create([
            'name' => 'Aguacate',
            'cost' => 2.50,
        ]);

        $this->assertDatabaseHas('ingredients', [
            'name' => 'Aguacate',
            'cost' => 2.50,
        ]);
    }

    /**
     * Test para verificar la relación entre ingredientes y recetas.
     */
    public function test_ingredient_can_belong_to_multiple_recipes()
    {
        $ingredient = Ingredient::factory()->create();
        $recipes = Recipe::factory()->count(3)->create();

        foreach ($recipes as $recipe) {
            $recipe->ingredients()->attach($ingredient->id);
        }

        $this->assertCount(3, $ingredient->recipes);
        foreach ($recipes as $recipe) {
            $this->assertTrue($ingredient->recipes->contains($recipe));
        }
    }
}
