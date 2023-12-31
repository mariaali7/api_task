<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use Database\Factories\IngredientFactory;

class IngredientsTableSeeder extends Seeder
{
  public function run()
  {
     IngredientFactory::new()->count(5)->create();
  }
}