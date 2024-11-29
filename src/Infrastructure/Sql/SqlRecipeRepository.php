<?php

namespace Src\Infrastructure\Sql;

use App\Models\Ingredient;
use App\Models\Recipe;
use Src\Domain\RecipeRepository;

class SqlRecipeRepository implements RecipeRepository
{
    public function create(array $data): Recipe
    {
        // Si existen ingredientes, se crean y se adjuntan a la receta
        $ingredientIds = [];
        if (isset($data['ingredients'])) {
            $ingredients = $data['ingredients'];
            unset($data['ingredients']);
            foreach ($ingredients as $ingredient) {
                $ingredientModel = Ingredient::firstOrCreate($ingredient);
                $ingredientIds[] = $ingredientModel->id;
            }
        }

        // Si existen subrecetas, se extraen para adjuntarlas más tarde
        if (isset($data['recipes'])) {
            $recipes = $data['recipes'];
            unset($data['recipes']);
        }

        // Crear la receta principal
        $recipe = Recipe::create($data);

        // Si se crearon ingredientes, adjuntarlos a la receta
        if (!empty($ingredientIds)) {
            $recipe->ingredients()->attach($ingredientIds);
        }

        // Si se definieron subrecetas, adjuntarlas a la receta
        if (!empty($recipes)) {
            $recipe->attachAsParentRecipes($recipes);
        }

        return $recipe;
    }

    public function getRecipeByName(string $name): ?Recipe
    {
        return Recipe::where('name', $name)->first();
    }

    public function getRecipeWithHighestCost(): string
    {
        $recipe = Recipe::get()
            ->sortByDesc(fn($recipe) => $recipe->getTotalCost())
            ->first();
        return $recipe->name . ' ' . number_format($recipe->getTotalCost(), 2);
    }

    public function getRecipeWithLowestCost(): string
    {
        $recipe = Recipe::get()
            ->sortBy(fn($r) => $r->getTotalCost())
            ->first();

        return $recipe->name . ' ' . number_format($recipe->getTotalCost(), 2);
    }

    public function getRecipeWithHighestProfitMargin(): string
    {
        $recipe = Recipe::get()
            ->sortByDesc(fn($r) => $r->getProfitMargin())
            ->first();

        return $recipe->name . ' ' . number_format($recipe->getProfitMargin(), 2) . '%';
    }

    public function getRecipeWithLowestProfitMargin(): string
    {
        $recipe = Recipe::get()
            ->where('sale_price', '>', 0)
            ->sortBy(fn($r) => $r->getProfitMargin())
            ->first();

        if (!$recipe) {
            return 'No hay recetas con margen de beneficio';
        }

        return $recipe->name . ' ' . number_format($recipe->getProfitMargin(), 2) . '%';
    }
}
