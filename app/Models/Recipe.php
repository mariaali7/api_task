<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['name', 'description'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }


    public function boxes()
    {
        return $this->belongsToMany(Box::class);
    }
}