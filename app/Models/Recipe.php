<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }


    public function boxes()
    {
        return $this->belongsToMany(Box::class, 'box_recipe');
    }

    public function RecipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
}