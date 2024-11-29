<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Src\Application\CreateRecipe;
use Src\Domain\RecipeRepository;
use Src\Infrastructure\Sql\SqlRecipeRepository;
use Throwable;

class RecipeController extends Controller
{
    private RecipeRepository $recipeRepository;

    public function __construct()
    {
        $this->recipeRepository = new SqlRecipeRepository;
    }

    /**
     * Crear una nueva receta.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->getData($request);
            $service = new CreateRecipe($this->recipeRepository);

            return response()->json($service($data), 201);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @throws Exception
     */
    private function getData(Request $request): array
    {
        $inputs = $request->all();

        $validatorRules = [
            'name' => 'required|string|unique:recipes',
            'sale_price' => 'required|numeric',
            'ingredients' => 'required|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.cost' => 'required|numeric',
        ];

        $validator = Validator::make($inputs, $validatorRules);

        if ($validator->fails()) {
            $errorMessages = $validator->errors()->all();
            $errorMessages = implode(', ', $errorMessages);
            throw new InvalidArgumentException($errorMessages);
        }

        return $validator->validated();
    }

    /**
     * Obtener la receta con el mayor coste.
     *
     * @return JsonResponse
     */
    public function getRecipeWithHighestCost(): JsonResponse
    {
        return response()->json($this->recipeRepository->getRecipeWithHighestCost());
    }

    /**
     * Obtener la receta con el menor coste.
     *
     * @return JsonResponse
     */
    public function getRecipeWithLowestCost(): JsonResponse
    {
        return response()->json($this->recipeRepository->getRecipeWithLowestCost());
    }

    /**
     * Obtener la receta con el mayor margen de beneficio.
     *
     * @return JsonResponse
     */
    public function getRecipeWithHighestProfitMargin(): JsonResponse
    {
        return response()->json($this->recipeRepository->getRecipeWithHighestProfitMargin());
    }

    /**
     * Obtener la receta con el menor margen de beneficio.
     *
     * @return JsonResponse
     */
    public function getRecipeWithLowestProfitMargin(): JsonResponse
    {
        return response()->json($this->recipeRepository->getRecipeWithLowestProfitMargin());
    }
}
