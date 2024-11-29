<?php

namespace Src\Application;

use Src\Domain\RecipeRepository;

final class CreateRecipe
{
    private RecipeRepository $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function __invoke(array $data): array
    {
        $ingredients = $data['ingredients'] ?? [];
        unset($data['ingredients']);

        // Identificar si el ingrediente es un subrecipe
        $recipes = [];
        $noRecipes = [];
        foreach ($ingredients as $ingredient) {
            $product = $this->recipeRepository->getRecipeByName($ingredient['name']);

            if ($product) {
                $recipes[] = $product->id;
            } else {
                $noRecipes[] = $ingredient;
            }
        }

        $data['recipes'] = $recipes;
        $data['ingredients'] = $noRecipes;

        $this->recipeRepository->create($data);

        return [
            'Receta con mayor coste: ' => $this->recipeRepository->getRecipeWithHighestCost(),
            'Receta con menor coste: ' => $this->recipeRepository->getRecipeWithLowestCost(),
            'Receta con mayor margen de beneficio: ' => $this->recipeRepository->getRecipeWithHighestProfitMargin(),
            'Receta con menor margen de beneficio: ' => $this->recipeRepository->getRecipeWithLowestProfitMargin(),
        ];
    }
}
