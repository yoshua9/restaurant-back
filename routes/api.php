<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::post('/recipes', [RecipeController::class, 'store']);
Route::post('/recipes/{recipeId}/sub-recipes', [RecipeController::class, 'assignSubRecipes']);
Route::get('/recipes/highest-cost', [RecipeController::class, 'getRecipeWithHighestCost']);
Route::get('/recipes/lowest-cost', [RecipeController::class, 'getRecipeWithLowestCost']);
Route::get('/recipes/highest-profit-margin', [RecipeController::class, 'getRecipeWithHighestProfitMargin']);
Route::get('/recipes/lowest-profit-margin', [RecipeController::class, 'getRecipeWithLowestProfitMargin']);
