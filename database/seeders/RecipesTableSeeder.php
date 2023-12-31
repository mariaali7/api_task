<?php
 namespace Database\Seeders; 

use App\Models\Recipe;
use Database\Factories\RecipeFactory;
use Illuminate\Database\Seeder;

class RecipesTableSeeder extends Seeder {
    public function run() {
        RecipeFactory::new()->count(10)->create();
    }}