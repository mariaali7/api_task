<?php
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/ingredients', [ApiController::class, 'createIngredient']);

Route::post('/recipes', [ApiController::class, 'createRecipe']);

Route::get('/recipes', [ApiController::class, 'listRecipes']);

Route::post('/boxes', [ApiController::class, 'createBox']);

Route::get('/ingredients/order', [ApiController::class, 'getIngredientsToOrder']);

Route::get('/ingredients/order', [ApiController::class, 'getIngredientsToOrder']);
