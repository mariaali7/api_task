<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    // ... existing properties and methods ...

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
