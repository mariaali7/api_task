<?php

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Recipe;
use Carbon\Carbon;
use App\Models\Box;




class ApiController extends Controller
{
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

        $box->recipes()->attach($validatedData['recipe_ids']);

        return response()->json($box, 201);
    }


    public function getIngredientsToOrder(Request $request)
    {
        $validatedData = $request->validate([
            'order_date' => 'required|date',
        ]);

        $orderDate = Carbon::createFromFormat('Y-m-d', $validatedData['order_date']);
        $endDate = $orderDate->copy()->addDays(6);

        $orders = Order::whereBetween('delivery_date', [$orderDate, $endDate])->get();

        $ingredientQuantities = [];

        $ingredientQuantities = $orders->flatMap(function ($order) {
            return $order->recipes->flatMap(function ($recipe) {
                return $recipe->ingredients->mapWithKeys(function ($ingredient) {
                    return [
                        $ingredient->id => $ingredient->pivot->amount
                    ];
                });
            });
        })->groupBy(function ($amount, $ingredientId) {
            return $ingredientId;
        })->map(function ($amounts) {
            return $amounts->sum();
        })->toArray();



$ingredientsToOrder = [];
foreach ($ingredientQuantities as $ingredientId => $amount) {
    $ingredient = Ingredient::find($ingredientId);
    $ingredientsToOrder[] = [
        'ingredient_id' => $ingredientId,
        'name' => $ingredient->name,
        'measure' => $ingredient->measure,
        'supplier' => $ingredient->supplier,
        'amount' => $amount,
    ];
}

return response()->json($ingredientsToOrder);
}
}