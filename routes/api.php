<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/ingredients', [ApiController::class, 'createIngredient']);

Route::post('/recipes', [ApiController::class, 'createRecipe']);

Route::get('/recipes', [ApiController::class, 'listRecipes']);

Route::post('/boxes', [ApiController::class, 'createBox']);

Route::get('/ingredients/order', [ApiController::class, 'getIngredientsToOrder']);
