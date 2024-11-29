<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sale_price'];

    /**
     * Relación con los ingredientes directos de la receta.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
            ->withTimestamps();
    }

    /**
     * Añade una receta como padre de esta receta.
     *
     * @param array|int $parentRecipeIds
     */
    public function attachAsParentRecipes(array|int $parentRecipeIds): void
    {
        // Convertir el ID a un array si es necesario
        $parentRecipeIds = is_array($parentRecipeIds) ? $parentRecipeIds : [$parentRecipeIds];

        foreach ($parentRecipeIds as $parentRecipeId) {
            // Verificar que no se auto-referencie
            if ($this->id === $parentRecipeId) {
                throw new \InvalidArgumentException('A recipe cannot be its own subrecipe.');
            }

            // Verificar que no cree ciclos en la jerarquía
            if ($this->isDescendantOf($parentRecipeId)) {
                throw new \InvalidArgumentException('A recipe cannot include any of its parent recipes.');
            }
        }

        // Realizar el attach
        $this->parentRecipes()->attach($parentRecipeIds);
    }

    /**
     * Comprueba si esta receta es descendiente de una receta dada.
     *
     * @param int $parentId
     * @return bool
     */
    public function isDescendantOf(int $parentId): bool
    {
        // Cargar los padres con eager loading
        $parents = $this->parentRecipes()->with('parentRecipes')->get();

        foreach ($parents as $parent) {
            if ($parent->id === $parentId || $parent->isDescendantOf($parentId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Relación con las recetas padre (las que incluyen esta receta).
     */
    public function parentRecipes(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'recipe_recipes', 'child_recipe_id', 'parent_recipe_id')
            ->withTimestamps();
    }

    /**
     * Relación con las subrecetas asociadas.
     */
    public function subRecipes(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'recipe_recipes', 'parent_recipe_id', 'child_recipe_id')
            ->withTimestamps();
    }

    public function getProfitMargin(): float
    {
        return $this->sale_price > 0 ? ($this->sale_price - $this->getTotalCost()) * 100 / $this->sale_price : 0;
    }

    public function getTotalCost(): float
    {
        $ingredientsCost = $this->ingredients->sum('cost');
        $parentRecipesCost = $this->parentRecipes->sum(fn($recipe) => $recipe->getTotalCost());
        return $ingredientsCost + $parentRecipesCost;
    }
}
