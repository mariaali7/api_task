<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Box;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use Mockery;

class BoxTest extends TestCase
{
    // use RefreshDatabase;

    public function testCreateBoxValidData()
    {
        // Create a user and some recipes
        $user = User::factory()->create();
        $recipes = Recipe::factory()->count(4)->create();
    

        // Make a POST request to the /api/boxes endpoint
        $response = $this->actingAs($user)->postJson('/api/boxes', [
            'delivery_date' => now()->addDays(3)->toDateString(),
            'recipe_ids' => $recipes->pluck('id')->toArray(),
        ]);

        // Assert that the response status is 201
        $response->assertStatus(201);

        // Assert the response structure
        $response->assertJsonStructure([
            'delivery_date',
            'user_id',
            'recipes' => [
                '*' => [ // Assuming each recipe has an id and a name
                    'id',
                    'name'
                ]
            ],
        ]);
    }
}