<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'measure', 'supplier'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class)->withPivot('amount');
    }
}