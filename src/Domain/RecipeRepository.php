<?php

namespace Src\Domain;

use App\Models\Recipe;

interface RecipeRepository
{
    public function create(array $data): Recipe;
    public function getRecipeByName(string $name): ?Recipe;
    public function getRecipeWithHighestCost(): string;
    public function getRecipeWithLowestCost(): string;
    public function getRecipeWithHighestProfitMargin(): string;
    public function getRecipeWithLowestProfitMargin(): string;
}
