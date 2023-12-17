<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = ['delivery_date', 'user_id'];

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class);
    }
}