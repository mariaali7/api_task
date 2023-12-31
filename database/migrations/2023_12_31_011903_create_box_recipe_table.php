<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxRecipeTable extends Migration
{
    public function up()
    {
        Schema::create('box_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            // other fields as necessary
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('box_recipe');
    }
}
