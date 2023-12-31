<?php
namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Recipe;
use Carbon\Carbon;
use App\Models\Box;
use App\Models\RecipeIngredients;
use Illuminate\Support\Facades\Validator;




class ApiController extends Controller
{

    public function indexIngredient()
    {
        $ingredients = Ingredient::all();
        return response()->json(['ingredients' => $ingredients], 201);
    }


    public function createIngredient(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'measure' => 'required|string',
            'supplier' => 'required|string',
        ]);

        $ingredient = Ingredient::create($validatedData);

        return response()->json($ingredient, 201);
    }



public function createRecipe(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'ingredients' => 'required|array',
        'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
        'ingredients.*.amount' => 'required|numeric|min:0',
    ]);

    $recipe = Recipe::create([
        'name' => $validatedData['name'],
        'description' => $validatedData['description'],
    ]);

    foreach ($validatedData['ingredients'] as $ingredient) {
        $recipe->ingredients()->attach($ingredient['ingredient_id'], ['amount' => $ingredient['amount']]);
    }

    return response()->json($recipe, 201);
}


public function listRecipes(Request $request)
{
    $perPage = $request->input('per_page', 10);

    $recipes = Recipe::paginate($perPage);

    return response()->json($recipes);
}


public function createBox(Request $request)
{
    $validatedData = $request->validate([
        'delivery_date' => 'required|date|after_or_equal:' . Carbon::now()->addHours(48)->toDateTimeString(),
        'recipe_ids' => 'required|array|min:1|max:4',
        'recipe_ids.*' => 'required|exists:recipes,id',
    ]);

    $box = Box::create([
        'delivery_date' => $validatedData['delivery_date'],
        'user_id' => $request->user()->id,
    ]);

    // Use 'sync' to update the pivot table with the array of recipe IDs
    $box->recipes()->sync($validatedData['recipe_ids']);

    return response()->json($box->load('recipes'), 201);

}


    public function indexBox(Request $request)
    {
        $perPage = $request->has('per_page') ? (int)$request->per_page : 10; // Number of items per page
        $boxes = Box::paginate($perPage);

        return response()->json($boxes, 200);
    }



    public function getIngredientsToOrder(Request $request)
    {
        $orderDate = Carbon::parse($request->input('order_date'));
        if (!$orderDate->isFuture()) {
            return response()->json(['error' => 'Invalid order_date. The date must be today or in the future.'], 400);
        }

        $endDate = $orderDate->copy()->addDays(7);
        $boxes = Box::with(['recipes.recipeIngredients.ingredient'])
                    ->whereBetween('delivery_date', [$orderDate, $endDate])
                    ->get();

        $ingredientsList = $boxes->flatMap(function ($box) {
            return $box->recipes->flatMap(function ($recipe) {
                return $recipe->recipeIngredients->map(function ($recipeIngredient) {
                    return [
                        'ingredient_id' => $recipeIngredient->ingredient->id,
                        'name' => $recipeIngredient->ingredient->name,
                        'measure' => $recipeIngredient->ingredient->measure,
                        'quantity_required' => $recipeIngredient->amount
                    ];
                });
            });
        })->groupBy('ingredient_id')
          ->map(function ($groupedIngredients) {
            return [
                'name' => $groupedIngredients->first()['name'],
                'measure' => $groupedIngredients->first()['measure'],
                'quantity_required' => $groupedIngredients->sum('quantity_required')
            ];
        })->values();


     
        return response()->json(['ingredients' => $ingredientsList]);
    }

}
