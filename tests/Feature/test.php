<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Http\Controllers\ApiController;

class IngredientControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function test_create_ingredient_valid_data()
    {
        $controller = new ApiController();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->andReturn([
            'name' => 'Test',
            'measure' => 'g',
            'supplier' => 'Supplier'
        ]);

        $response = $controller->createIngredient($request);

        $this->assertEquals(201, $response->getStatusCode());

        $ingredient = Ingredient::first();
        $this->assertEquals('Test', $ingredient->name);
    }
}