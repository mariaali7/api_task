<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Http\Controllers\ApiController;

class RecipeTest extends TestCase
{
    // use RefreshDatabase;

    public function testCreateRecipeValidData()
    {
        $controller = new ApiController();

        // Mock the Request with expected input
        $ingredient1 = Ingredient::create(['name' => 'Flour', 'measure' => 'g', 'supplier' => 'Supplier1']);
        $ingredient2 = Ingredient::create(['name' => 'Sugar', 'measure' => 'g', 'supplier' => 'Supplier2']);

        $mockedRequestData = [
            'name' => 'Chocolate Cake',
            'description' => 'Delicious and rich chocolate cake',
            'ingredients' => [
                ['ingredient_id' => $ingredient1->id, 'amount' => 500],
                ['ingredient_id' => $ingredient2->id, 'amount' => 200]
            ]
        ];

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn($mockedRequestData);

        // Act
        $response = $controller->createRecipe($request);

        // Assert status code
        $this->assertEquals(201, $response->getStatusCode());

        // Assert that the recipe is stored in the database
        $recipe = Recipe::first();
        $this->assertEquals('Chocolate Cake', $recipe->name);

        // Assert ingredients are attached to the recipe
        $attachedIngredients = $recipe->ingredients;
        $this->assertCount(2, $attachedIngredients);
        $this->assertEquals('Flour', $attachedIngredients[0]->name);
        $this->assertEquals('Sugar', $attachedIngredients[1]->name);
    }
}
