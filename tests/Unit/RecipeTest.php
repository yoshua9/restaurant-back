<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify a recipe can be created with ingredients.
     */
    public function test_recipe_can_be_created_with_ingredients()
    {
        $recipe = Recipe::factory()->create();
        $ingredients = Ingredient::factory(3)->create();

        $recipe->ingredients()->attach($ingredients->pluck('id')->toArray());

        $this->assertCount(3, $recipe->ingredients);
        $this->assertEquals($ingredients->pluck('id')->toArray(), $recipe->ingredients->pluck('id')->toArray());
    }

    /**
     * Test to verify a recipe cannot be its own subrecipe.
     */
    public function test_recipe_cannot_be_its_own_subrecipe()
    {
        $recipe = Recipe::factory()->create();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A recipe cannot be its own subrecipe.');

        $recipe->attachAsParentRecipes($recipe->id);
    }

    /**
     * Test to verify a recipe cannot include any of its parent recipes.
     */
    public function test_recipe_cannot_include_any_of_its_parent_recipes()
    {
        $grandparentRecipe = Recipe::factory()->create(['name' => 'Grandparent Recipe']);
        $parentRecipe = Recipe::factory()->create(['name' => 'Parent Recipe']);
        $childRecipe = Recipe::factory()->create(['name' => 'Child Recipe']);

        // Create hierarchy: grandparent -> parent -> child
        $parentRecipe->attachAsParentRecipes($grandparentRecipe->id);
        $childRecipe->attachAsParentRecipes($parentRecipe->id);

        // Attempt to add "grandparent" as a subrecipe of "child"
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('A recipe cannot include any of its parent recipes.');

        $childRecipe->attachAsParentRecipes($grandparentRecipe->id);
    }

    /**
     * Test to verify the profit margin calculation.
     */
    public function test_profit_margin_calculation()
    {
        $recipe = Recipe::factory()->create(['sale_price' => 20]);
        $ingredients = Ingredient::factory(2)->create(['cost' => 5]);
        $recipe->ingredients()->attach($ingredients->pluck('id')->toArray());

        $expectedProfitMargin = (($recipe->sale_price - 10) * 100) / $recipe->sale_price;

        $this->assertEquals($expectedProfitMargin, $recipe->getProfitMargin());
    }

    /**
     * Test to verify total cost calculation for a recipe with subrecipes.
     */
    public function test_total_cost_calculation_with_subrecipes()
    {
        $parentRecipe = Recipe::factory()->create();
        $childRecipe = Recipe::factory()->create();
        $ingredients = Ingredient::factory(2)->create(['cost' => 3]);

        $parentRecipe->ingredients()->attach($ingredients->pluck('id')->toArray());
        $childRecipe->attachAsParentRecipes($parentRecipe->id);

        $expectedTotalCost = 6; // Ingredients cost from parent
        $this->assertEquals($expectedTotalCost, $childRecipe->getTotalCost());
    }

    /**
     * Test to verify parent and child recipe relationships.
     */
    public function test_recipe_parent_and_child_relationships()
    {
        $parentRecipe = Recipe::factory()->create();
        $childRecipe = Recipe::factory()->create();

        $childRecipe->attachAsParentRecipes($parentRecipe->id);

        $this->assertTrue($childRecipe->parentRecipes->contains($parentRecipe));
        $this->assertTrue($parentRecipe->subRecipes->contains($childRecipe));
    }
}
